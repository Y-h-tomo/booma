<?php


namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Cake\Mailer\Mailer;

class SendMailCommand extends Command
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('TBooks');
        $this->loadModel('MUsers');
        $this->loadModel('THistories');
    }
    public function execute(Arguments $args, ConsoleIo $io)
    {

          $time = FrozenTime::now()->modify("+24 hours");
          $tHistories = $this->THistories->find()->contain(['MUsers','TBooks'])->where(['TBooks.del_flg' => 0,'MUsers.del_flg'=> 0,'THistories.return_time <' => $time])->toArray();
          debug($tHistories);
          exit;
          foreach($tHistories as $history){
              $mailer = new Mailer();
              $mailer->setSender('me@example.com','Booma')
                  ->setDomain('www.example.org')
                  ->setTo($history->m_user['email'])
                  ->setSubject("Booma: {$history->t_book['name']}の返却期限のご連絡")
                  ->viewBuilder()
                      ->setTemplate('default')
                      ->setVar('message', 'こんにちは、世界！');
              $mailer->deliver();
      }
    }
}