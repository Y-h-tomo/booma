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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Cake\Mailer\Mailer;

class SendEmailCommand extends Command
{
  public function initialize(): void
  {
    parent::initialize();
    $this->loadModel('THistories');
    $this->loadModel('TBooks');
    $this->loadModel('MUsers');
  }

  public function execute(Arguments $args, ConsoleIo $io)
  {
    Log::write('notice', '==[start]定時メール送信開始==');

    // レンタル記録のうち、12時間前のものと延滞しているデータを取得
    $nearTime = FrozenTime::now()->modify("+12 hours");
    $overTime =  FrozenTime::now();
    $safeHistories = $this->THistories->find()->contain(['MUsers', 'TBooks'])->where(['TBooks.del_flg' => 0, 'MUsers.del_flg' => 0, 'THistories.del_flg' => 0, 'THistories.return_time <' => $nearTime, 'THistories.return_time >' => $overTime])->toArray();
    $outHistories = $this->THistories->find()->contain(['MUsers', 'TBooks'])->where(['TBooks.del_flg' => 0, 'MUsers.del_flg' => 0, 'THistories.del_flg' => 0, 'THistories.return_time <' => $overTime])->toArray();

    $nearLog = '';
    $outLog = '';

    // 返却期限まで12時間きっている履歴のユーザーへメール送信
    Log::write('notice', '----[start]期限12時間前メール送信開始----');

    if (!empty($safeHistories)) {
      $nearCount = count($safeHistories);
      Log::write('notice', "*期限12時間前：Email送信全件：{$nearCount}");
      $i = 1;
      foreach ($safeHistories as $history) {
        $historyLog = "";
        $returnTime = $history->return_time->i18nFormat('yyyy-MM-dd HH:mm:ss');
        // $setTo = $history->m_user['email'];
        $setTo = 'y-haraguchi@spark.ne.jp';
        $mailer = new Mailer();
        $mailer->setEmailFormat('text')
          ->setTo($setTo)
          ->setFrom(['booma@info.co' => 'Booma'])
          ->setSubject("Booma: 書籍名：{$history->t_book['name']}【書籍No．{$history->t_book['book_no']} 】の返却期限間近のご連絡")
          ->deliver("{$history->t_book['name']}の返却期限が12時間をきっております。\n返却期限：{$returnTime}までに返却処理をお願い致します。");
        $historyLog = "（{$i}/{$nearCount}）送付先：{$setTo}、レンタルID：{$history->id}、返却期限：{$returnTime}";
        Log::write('notice', $historyLog);
        $i++;
      }
      $i--;
      $nearLog = ":::[near--result]期限12時間前のメール送信対象：全件：{$nearCount}、送付件数：{$i}";
    } else {
      Log::write('notice', "*期限12時間前：Email送信全件：0");
      $nearLog = ':::[near--result]期限12時間前のメール送信対象はありません。';
    }

    Log::write('notice', '----[end]期限12時間前メール送信終了----');


    // 延滞している履歴のユーザーへメール送信
    Log::write('notice', '----[start]延滞メール送信開始----');

    if (!empty($outHistories)) {
      $outCount = count($outHistories);
      Log::write('notice', "*延滞：Email送信全件：{$outCount}");
      $i = 1;
      foreach ($outHistories as $history) {
        $historyLog = "";
        $returnTime = $history->return_time->i18nFormat('yyyy-MM-dd HH:mm:ss');
        // $setTo = $history->m_user['email'];
        $setTo = 'y-haraguchi@spark.ne.jp';
        $mailer = new Mailer();
        $mailer->setEmailFormat('text')
          ->setTo($setTo)
          ->setFrom(['booma@info.co' => 'Booma'])
          ->setSubject("Booma: 書籍名：{$history->t_book['name']}【書籍No．{$history->t_book['book_no']} 】の返却期限延滞のご連絡")
          ->deliver("{$history->t_book['name']}の返却期限が過ぎております。\n返却処理をお願い致します。");
        $historyLog = "（{$i}/{$outCount}）送付先：{$setTo}、レンタルID：{$history->id}、返却期限：{$returnTime}";
        Log::write('notice', $historyLog);
        $i++;
      }
      $i--;
      $outLog = ":::[out--result]延滞ログ延滞のメール送信対象：全件：{$outCount}、送付件数：{$i}";
    } else {
      Log::write('notice', "*延滞：Email送信全件：0");
      $outLog = ':::[out--result]延滞のメール送信対象はありません。';
    }
    Log::write('notice', '----[end]延滞メール送信終了----');


    // 結果をログへ出力
    Log::write('notice', $nearLog);
    Log::write('notice', $outLog);

    Log::write('notice', '==[end]定時メール送信終了==');
  }
}