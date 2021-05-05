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

class ImageCleanCommand extends Command
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('TBooks');
      }

      public function execute(Arguments $args, ConsoleIo $io)
      {
        $time = FrozenTime::now();
        // 画像アップロードフォルダ指定
        $dir = "/var/www/html/booma/webroot/img/";
        // 全ての画像名取得
        $allImages = glob("{$dir}*.*");
        // 必要な画像名配列
        $onImage = [
          $dir."cake-logo.png",
          $dir."cake.icon.png",
          $dir."cake.logo.svg",
          $dir."cake.power.gif",
          $dir."caret-down-solid.svg"
        ];
        // DB上で論理削除されていない書籍画像名取得
        $tBooks = $this->TBooks->find()->where(['del_flg' => 0])->toArray();
        // 必要な画像名配列に加える
        foreach($tBooks as $book){
          $onImage[] = $dir.$book->image;
        }

        // 全ての画像と必要な画像名配列の差分取得
        $trashImages = array_diff($allImages, $onImage);
        // 差分＝不必要な画像を削除
        foreach($trashImages as $trash){
          unlink($trash);
        }
        $log = "";
        foreach($trashImages as $file){
          $log  .= $time.": unlink :".$file.PHP_EOL;
        }
        Log::write('debug', $log);

        // $io->out(print_r($log, true));
    }
}