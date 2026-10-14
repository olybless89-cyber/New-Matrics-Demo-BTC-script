<?php

namespace App\Controllers;

class KycController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $kyc = \DB::fetch("SELECT * FROM kyc_verifications WHERE user_id = ? ORDER BY id DESC LIMIT 1", [$user['id']]);
        \View::render('kyc.index', [
            'active' => 'kyc',
            'title' => 'KYC Verification',
            'kyc' => $kyc,
        ], 'dashboard');
    }

    public function store()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $userId = $user['id'];

        $existing = \DB::fetch("SELECT * FROM kyc_verifications WHERE user_id = ? ORDER BY id DESC LIMIT 1", [$userId]);
        if ($existing && $existing['status'] === 'pending') {
            flash('error', 'Your KYC verification is already pending review.');
            redirect('dashboard/kyc');
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $dob = trim($_POST['date_of_birth'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $idType = trim($_POST['id_type'] ?? '');
        $idNumber = trim($_POST['id_number'] ?? '');

        if (empty($fullName) || empty($idType)) {
            flash('error', 'Full name and ID type are required.');
            redirect('dashboard/kyc');
        }

        $uploadDir = ROOT . '/public/uploads/kyc/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $idFront = $existing['id_front'] ?? null;
        $idBack = $existing['id_back'] ?? null;
        $selfie = $existing['selfie'] ?? null;

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxSize = 5 * 1024 * 1024;

        foreach (['id_front', 'id_back', 'selfie'] as $field) {
            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    flash('error', "{$field}: Only JPG, PNG, PDF allowed.");
                    redirect('dashboard/kyc');
                }
                if ($_FILES[$field]['size'] > $maxSize) {
                    flash('error', "{$field}: File too large (max 5MB).");
                    redirect('dashboard/kyc');
                }
                $filename = $userId . '_' . $field . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $filename)) {
                    $$field = 'uploads/kyc/' . $filename;
                }
            }
        }

        if ($existing) {
            \DB::update('kyc_verifications', [
                'full_name' => $fullName,
                'date_of_birth' => $dob ?: null,
                'address' => $address,
                'city' => $city,
                'country' => $country,
                'id_type' => $idType,
                'id_number' => $idNumber,
                'id_front' => $idFront,
                'id_back' => $idBack,
                'selfie' => $selfie,
                'status' => 'pending',
                'admin_note' => null,
                'reviewed_at' => null,
            ], 'id = :id', ['id' => $existing['id']]);
        } else {
            \DB::insert('kyc_verifications', [
                'user_id' => $userId,
                'full_name' => $fullName,
                'date_of_birth' => $dob ?: null,
                'address' => $address,
                'city' => $city,
                'country' => $country,
                'id_type' => $idType,
                'id_number' => $idNumber,
                'id_front' => $idFront,
                'id_back' => $idBack,
                'selfie' => $selfie,
                'status' => 'pending',
            ]);
        }

        flash('success', 'KYC verification submitted. It will be reviewed shortly.');
        redirect('dashboard/kyc');
    }
}
