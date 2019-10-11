<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronsTasks extends Command
{
  // the name of the command (the part after "bin/console")
  protected static $defaultName = 'app:cron';

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $timeNow = time();
    $tabFiles = array_slice(scandir('public/zip'), 2);

    foreach($tabFiles as $file){
      if(($timeNow - filectime(getcwd().'\\public\\zip\\'.$file)) >= 172800){
        unlink(getcwd().'\\public\\zip\\'.$file);
      }
    }
  }
}
