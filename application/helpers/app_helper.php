<?php
use Orm\User;

function generate_activation_code()
{
    $activation_code = rand(10000,99999);
    $user_list = User::where(['stts' => 0, 'activation_code' => $activation_code])->get();
    if ($user_list->isNotEmpty()) {
        $activation_code = generate_activation_code();
    }
    return $activation_code;
}

function send_activation_email($user)
{
    $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['SIB_API_KEY']);

    $apiInstance = new \SendinBlue\Client\Api\TransactionalEmailsApi(
            new \GuzzleHttp\Client(),
            $config
        );
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
    $sendSmtpEmail['subject'] = 'Activation User';
    $sendSmtpEmail['htmlContent'] = '<html><body>'
                    . '<p>Mohon aktifasi akun anda dengan klik link dibawah ini</p>'
                    . '<p><a href="' . site_url('activation/') . $user->activation_code . '">Link Aktivasi</a></p>'
                    . ' </body></html>';
    $sendSmtpEmail['sender'] = ['name' => 'SATGAS HAJI 2022', 'email' => 'no-reply@kanwiljateng.app'];
    $sendSmtpEmail['to'] = [
        ['email' => $user->email, 'name' => $user->fullname]
    ];
    // $sendSmtpEmail['cc'] = [
    //     ['email' => 'ansanwan@gmail.com']
    // ];
    $sendSmtpEmail['bcc'] = [
        ['email' => 'idrez.mochamad@gmail.com']
    ];
    // $sendSmtpEmail['replyTo'] = ['email' => 'dev.jateng@gmail.com', 'name' => 'SATGAS HAJI'];
    // $sendSmtpEmail['headers'] = ['Some-Custom-Name' => 'unique-id-123456'];
    $sendSmtpEmail['params'] = ['parameter' => '', 'subject' => ''];

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        $user->is_sent_activation_code = 1;
        $user->save();
        // dd($result);
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function send_activation_email_bulk($user_list)
{
    if ($user_list->isNotEmpty()) {
        $cron_end = date('Y-m-d H:i:s', strtotime('+1 minutes'));
        foreach ($user_list as $user) {
            $now = date('Y-m-d H:i:s');
            if ($now > $cron_end) {
                return;
            }
            send_activation_email($user);
        }
    }
}