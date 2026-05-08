<?php

class Home extends Controller {
    public function index() {
        $data = [
            'title' => 'DenSmart - Modern Dental Clinic Management System'
        ];
        $this->view('home/index', $data);
    }

    public function requestDemo() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            // Sanitize data
            $company = htmlspecialchars($_POST['company'] ?? '');
            $mobile = htmlspecialchars($_POST['mobile'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $country = htmlspecialchars($_POST['country'] ?? '');
            $note = htmlspecialchars($_POST['note'] ?? '');

            // Target email
            $to = "ajitsands@gmail.com";
            $subject = "New Demo Request from DenSmart Landing Page";
            $message = "Company: $company\nMobile: $mobile\nEmail: $email\nCountry: $country\nNote: $note";
            $headers = "From: noreply@sandslab.com";

            // Note: PHP mail() requires a configured SMTP server
            // @mail($to, $subject, $message, $headers);

            echo json_encode(['status' => 'success', 'message' => 'Thank you for your interest! We will contact you soon.']);
            exit;
        }
    }
}
