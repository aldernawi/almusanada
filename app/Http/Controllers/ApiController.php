<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function forms()
    {
        $forms = auth()->user()->forms()->withCount('submissions')->get();
        
        return response()->json([
            'success' => true,
            'forms' => $forms
        ]);
    }

    public function formDetails(Form $form)
    {
        if ($form->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this form'
            ], 403);
        }

        $form->load('fields');
        $submissions = $form->submissions()->with('submissionData.field')->latest()->get();

        $rows = $submissions->map(function ($sub) use ($form) {
            $row = [
                'id' => $sub->id,
                'submitted_at' => optional($sub->submitted_at)->toIso8601String(),
                'status' => $sub->status,
                'ip_address' => $sub->ip_address,
            ];
            $dataMap = [];
            foreach ($sub->submissionData as $sd) {
                $dataMap[$sd->field_id] = $sd->value;
            }
            foreach ($form->fields as $field) {
                $row[$field->label] = $dataMap[$field->id] ?? null;
            }
            return $row;
        });

        return response()->json([
            'success' => true,
            'form' => [
                'id' => $form->id,
                'title' => $form->title,
                'fields' => $form->fields->map(fn($f) => [
                    'id' => $f->id,
                    'label' => $f->label,
                    'type' => $f->field_type,
                ])->values(),
            ],
            'rows' => $rows,
            'total' => $rows->count(),
        ]);
    }

    public function submissions(Form $form)
    {
        if ($form->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this form'
            ], 403);
        }

        $submissions = $form->submissions()->with('submissionData.field')->get()->map(function($sub) {
            $data = [];
            foreach ($sub->submissionData as $item) {
                $data[$item->field->label] = $item->value;
            }
            return [
                'id' => $sub->id,
                'submitted_at' => $sub->submitted_at,
                'status' => $sub->status,
                'ip_address' => $sub->ip_address,
                'data' => $data
            ];
        });

        return response()->json([
            'success' => true,
            'form' => [
                'id' => $form->id,
                'title' => $form->title
            ],
            'submissions' => $submissions
        ]);
    }
}
