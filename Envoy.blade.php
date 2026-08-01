@servers(['web' => ['user@your-server.com']])

@setup
    $repository = 'git@github.com:your-org/almusanada.git';
    $releases_dir = '/var/www/almusanada/releases';
    $app_dir = '/var/www/almusanada';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir . '/' . $release;
@endsetup

@story('deploy')
    clone_repository
    install_dependencies
    run_migrations
    optimize_application
    update_symlinks
    restart_queue
    cleanup_old_releases
    health_check
@endstory

@task('clone_repository')
    echo 'Cloning repository...'
    [ -d {{ $releases_dir }} ] || mkdir -p {{ $releases_dir }}
    git clone --depth 1 --branch {{ $branch ?? 'main' }} {{ $repository }} {{ $new_release_dir }}
    echo 'Repository cloned to {{ $new_release_dir }}'
@endtask

@task('install_dependencies')
    echo 'Installing composer dependencies...'
    cd {{ $new_release_dir }}
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    echo 'Dependencies installed'
@endtask

@task('run_migrations')
    echo 'Running database migrations...'
    cd {{ $new_release_dir }}
    php artisan migrate --force --no-interaction
    echo 'Migrations completed'
@endtask

@task('optimize_application')
    echo 'Optimizing application...'
    cd {{ $new_release_dir }}
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    echo 'Optimization completed'
@endtask

@task('update_symlinks')
    echo 'Updating symlinks...'
    # Link persistent storage
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage
    
    # Link .env file
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env
    
    # Link current to new release (atomic operation)
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
    
    # Update document root symlink
    sudo ln -nfs {{ $app_dir }}/current/public {{ $app_dir }}/public_html
    
    echo 'Symlinks updated'
@endtask

@task('restart_queue')
    echo 'Restarting queue workers...'
    cd {{ $new_release_dir }}
    php artisan queue:restart
    php artisan horizon:terminate 2>/dev/null || true
    echo 'Queue workers restarted'
@endtask

@task('cleanup_old_releases')
    echo 'Cleaning up old releases...'
    cd {{ $releases_dir }}
    # Keep only last 3 releases
    ls -t | tail -n +4 | xargs -r rm -rf
    echo 'Old releases cleaned up'
@endtask

@task('health_check')
    echo 'Performing health check...'
    sleep 2
    curl -s -o /dev/null -w '%{http_code}' {{ $health_url ?? 'https://almusanada.com/health' }}
    echo 'Health check completed'
@endtask

@task('rollback')
    echo 'Rolling back to previous release...'
    cd {{ $releases_dir }}
    # Get second most recent release
    previous_release=$(ls -t | head -n 2 | tail -n 1)
    if [ -n "$previous_release" ]; then
        ln -nfs {{ $releases_dir }}/$previous_release {{ $app_dir }}/current
        sudo ln -nfs {{ $app_dir }}/current/public {{ $app_dir }}/public_html
        cd {{ $releases_dir }}/$previous_release
        php artisan queue:restart
        echo "Rolled back to: $previous_release"
    else
        echo 'No previous release found!'
        exit 1
    fi
@endtask
