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

class ImageCleanCommand extends Command
{
  public function initialize(): void
  {
    parent::initialize();
    $this->loadModel('TBooks');
  }

  public function execute(Arguments $args, ConsoleIo $io)
  {

    Log::write('notice', '==[start]不要アップロード画像の削除処理開始==');

    // 画像アップロードフォルダ指定
    $dir = "/var/www/html/booma/webroot/img/";
    // 全ての画像名取得
    $allImages = glob("{$dir}*.*");
    $allCount = count($allImages);

    // 必要な画像名配列
    $needImages = [
      $dir . "cake-logo.png",
      $dir . "cake.icon.png",
      $dir . "cake.logo.svg",
      $dir . "cake.power.gif"
    ];

    // DB上で論理削除されていない書籍画像名取得
    $tBooks = $this->TBooks->find()->where(['del_flg' => 0])->toArray();

    // 必要な画像名配列に加える
    foreach ($tBooks as $book) {
      $needImages[] = $dir . $book->image;
    }

    // 全ての画像と必要な画像名配列の差分取得
    $trashImages = array_diff($allImages, $needImages);
    // 削除件数のログ
    $trashCount = count($trashImages);
    // Log::write('notice', "削除対象全件：{$trashCount}");

    $log = '';
    if (!empty($trashImages)) {

      $ignoreCount = 0;
      $errorCount = 0;
      $completeCount = 0;
      $i = 1;
      foreach ($trashImages as $trash) {
        //削除対象の時間を見る関数
        if ($this->timeCheck($trash)) {
          unlink($trash);
          if (file_exists($trash)) {
            Log::write('notice', "削除失敗({$i}/{$trashCount})：{$trash}");
            $errorCount++;
          } else {
            Log::write('notice', "削除完了({$i}/{$trashCount})：{$trash}");
            $completeCount++;
          }
        } else {
          Log::write('notice', "削除対象外({$i}/{$trashCount})：{$trash}");
          $ignoreCount++;
        }
        $i++;
      }
      $log = ":::全件：{$allCount}、削除対象全件：{$trashCount}、成功件数：{$completeCount}、失敗件数：{$errorCount}、削除対象外：{$ignoreCount}";
    } else {
      $log = ":::削除対象画像がありません。";
    }

    Log::write('notice', $log);
    Log::write('notice', '==[end]不要アップロード画像の削除処理終了==');
  }


  protected function timeCheck($trash)
  {
    // ファイル名からタイムスタンプ取得、登録が24時間前までのものは削除対象から外す
    $time = FrozenTime::now()->toUnixString();
    $trashTime = strtotime(substr($trash, 32, 14));
    $diff = $time - $trashTime;
    if ($diff < DAY) {
      return false;
    } else {
      return true;
    }
  }
}
