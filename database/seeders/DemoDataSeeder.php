<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use App\Models\SubmissionData;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@almusanada.sa'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Reviewers
        $reviewers = [];
        for ($i = 1; $i <= 2; $i++) {
            $reviewers[] = User::firstOrCreate(
                ['email' => "reviewer{$i}@almusanada.sa"],
                [
                    'name' => "مدقق {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'reviewer',
                    'email_verified_at' => now(),
                ]
            );
        }

        // 3. Create Forms (30 forms for demo, scalable to 200+)
        $formTemplates = [
            ['title' => 'مطالبة تأمين صحي - زيارة طوارئ', 'fields' => 8],
            ['title' => 'مطالبة تأمين صحي - عملية جراحية', 'fields' => 12],
            ['title' => 'مطالبة تأمين صحي - فحوصات مخبرية', 'fields' => 6],
            ['title' => 'مطالبة تأمين صحي - أشعة تشخيصية', 'fields' => 7],
            ['title' => 'مطالبة تأمين صحي - علاج طبيعي', 'fields' => 5],
            ['title' => 'مطالبة تأمين صحي - أدوية مزمنة', 'fields' => 9],
            ['title' => 'مطالبة تأمين صحي - ولادة', 'fields' => 10],
            ['title' => 'مطالبة تأمين صحي - أسنان', 'fields' => 6],
            ['title' => 'مطالبة تأمين صحي - نظارات طبية', 'fields' => 4],
            ['title' => 'مطالبة تأمين صحي - إقامة مستشفى', 'fields' => 11],
        ];

        $fieldTypes = ['text', 'textarea', 'email', 'number', 'date', 'file', 'select', 'checkbox'];

        for ($f = 0; $f < 30; $f++) {
            $template = $formTemplates[$f % count($formTemplates)];

            $form = Form::create([
                'user_id' => $superAdmin->id,
                'title' => $template['title'] . ' #' . ($f + 1),
                'description' => 'نموذج إلكتروني لتقديم مطالبة التأمين الصحي',
                'status' => 'active',
                'slug' => Str::random(48),
                'require_login' => false,
            ]);

            // Create form fields
            $fieldCount = $template['fields'];
            for ($fld = 1; $fld <= $fieldCount; $fld++) {
                $type = $fieldTypes[array_rand($fieldTypes)];
                FormField::create([
                    'form_id' => $form->id,
                    'field_type' => $type,
                    'label' => 'حقل ' . $fld . ' - ' . $this->getFieldLabel($type),
                    'placeholder' => 'أدخل البيانات هنا',
                    'required' => $fld <= 3, // First 3 fields are required
                    'order' => $fld,
                    'options' => in_array($type, ['select', 'checkbox', 'radio']) ? ['خيار 1', 'خيار 2', 'خيار 3'] : null,
                ]);
            }
        }

        // 3b. Assign forms to reviewers (split evenly)
        $allForms = Form::all();
        foreach ($allForms as $index => $form) {
            $reviewer = $reviewers[$index % count($reviewers)];
            $form->reviewers()->syncWithoutDetaching([$reviewer->id]);
        }

        // 4. Create Sample Submissions (pending for auditors to see)
        $forms = Form::all();

        foreach ($forms->take(15) as $i => $form) {
            $statuses = ['pending', 'pending', 'pending', 'approved', 'rejected'];
            $status = $statuses[$i % count($statuses)];
            $reviewerId = ($status !== 'pending') ? $reviewers[$i % count($reviewers)]->id : null;

            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'user_id' => null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'submitted_at' => now()->subDays(rand(1, 30)),
                'status' => $status,
                'reviewed_by' => $reviewerId,
                'reviewed_at' => $reviewerId ? now()->subDays(rand(0, 5)) : null,
                'review_notes' => $status === 'rejected' ? 'المطالبة غير مكتملة، يرجى إرفاق المستندات المطلوبة' : ($status === 'approved' ? 'تمت المراجعة والموافقة' : null),
                'metadata' => [
                    'submitted_by' => 'زائر',
                ],
            ]);

            // Create submission data for each field
            foreach ($form->fields as $field) {
                $value = match ($field->field_type) {
                    'email' => 'patient' . rand(1, 1000) . '@email.com',
                    'number' => (string) rand(1000000000, 9999999999),
                    'date' => now()->subYears(rand(20, 60))->format('Y-m-d'),
                    'file' => null,
                    'checkbox' => json_encode(['خيار 1', 'خيار 2']),
                    'select' => 'خيار 1',
                    default => 'بيانات تجريبية للحقل ' . $field->label,
                };

                SubmissionData::create([
                    'submission_id' => $submission->id,
                    'field_id' => $field->id,
                    'value' => $value,
                    'file_data' => $field->field_type === 'file' ? [
                        'name' => 'report.pdf',
                        'size' => 102400,
                        'type' => 'application/pdf',
                        'path' => 'submissions/' . $submission->id . '/files/report.pdf',
                    ] : null,
                ]);
            }
        }

        $this->command->info('=== Demo Data Created Successfully ===');
        $this->command->info('Users: ' . User::count() . ' (1 admin, 2 reviewers)');
        $this->command->info('Forms: ' . Form::count());
        $this->command->info('Submissions: ' . FormSubmission::count());
        $this->command->info('Reviewer Assignments: ' . \DB::table('form_reviewer')->count());
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('  Admin:    admin@almusanada.sa / password');
        $this->command->info('  Reviewer1: reviewer1@almusanada.sa / password');
        $this->command->info('  Reviewer2: reviewer2@almusanada.sa / password');
    }

    private function getFieldLabel(string $type): string
    {
        return match ($type) {
            'text' => 'نص قصير',
            'textarea' => 'نص طويل',
            'email' => 'بريد إلكتروني',
            'number' => 'رقم',
            'date' => 'تاريخ',
            'file' => 'ملف مرفق',
            'select' => 'قائمة منسدلة',
            'checkbox' => 'خيارات متعددة',
            default => 'حقل عام',
        };
    }
}
