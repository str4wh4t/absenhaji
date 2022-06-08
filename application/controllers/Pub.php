<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\User;

class Pub extends CI_Controller
{
    public function index()
    {
        show_404();
    }

    public function mailer()
    {
        $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['SIB_API_KEY']);

        $apiInstance = new \SendinBlue\Client\Api\TransactionalEmailsApi(
            new GuzzleHttp\Client(),
            $config
        );
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();

        $user_list = User::where(['stts' => 0, 'is_sent_activation_code' => 0])->get();

        if ($user_list->isNotEmpty()) {
            $cron_end = date('Y-m-d H:i:s', strtotime('+1 minutes'));
            foreach ($user_list as $user) {
                $now = date('Y-m-d H:i:s');
                if ($now > $cron_end) {
                    return;
                }
                $sendSmtpEmail['subject'] = 'Activation User';
                $sendSmtpEmail['htmlContent'] = '<html><body>'
                    . '<p>Mohon aktifasi akun anda dengan klik link dibawah ini</p>'
                    . '<p><a href="' . site_url('activation/') . $user->activation_code . '">Link Aktivasi</a></p>'
                    . ' </body></html>';
                $sendSmtpEmail['sender'] = ['name' => 'SATGAS HAJI', 'email' => 'dev.jateng@gmail.com'];
                $sendSmtpEmail['to'] = [
                    ['email' => $user->email, 'name' => $user->fullname]
                ];
                $sendSmtpEmail['cc'] = [
                    ['email' => 'ansanwan@gmail.com']
                ];
                $sendSmtpEmail['bcc'] = [
                    ['email' => 'idrez.mochamad@gmail.com']
                ];
                $sendSmtpEmail['replyTo'] = ['email' => 'dev.jateng@gmail.com', 'name' => 'SATGAS HAJI'];
                // $sendSmtpEmail['headers'] = ['Some-Custom-Name' => 'unique-id-123456'];
                $sendSmtpEmail['params'] = ['parameter' => '', 'subject' => ''];

                try {
                    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
                    $user->is_sent_activation_code = 1;
                    $user->save();
                    // dd($result);
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            }
        }
    }
}