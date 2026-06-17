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
            $subject = "New Demo Request - DenSmart ($company)";
            
            $body = "<h2>New Lead from DenSmart Website</h2>";
            $body .= "<p><strong>Clinic/Company:</strong> $company</p>";
            $body .= "<p><strong>Mobile:</strong> $mobile</p>";
            $body .= "<p><strong>Email:</strong> $email</p>";
            $body .= "<p><strong>Country:</strong> $country</p>";
            $body .= "<p><strong>Message:</strong><br>$note</p>";
            $body .= "<hr><p><small>This email was sent from the DenSmart automated demo request system.</small></p>";

            // Headers for HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: DenSmart Leads <noreply@densmart.us>" . "\r\n";
            $headers .= "Reply-To: $email" . "\r\n";

            // Send email
            @mail($to, $subject, $body, $headers);

            echo json_encode(['status' => 'success', 'message' => 'Thank you for your interest! We will contact you soon.']);
            exit;
        }
    }
}
