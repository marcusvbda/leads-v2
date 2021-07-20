<?php
namespace App\Logging;
use \Monolog\Handler\RotatingFileHandler;
class CustomLog extends RotatingFileHandler
{
  public $filenameFormat;
  protected function write(array $record): void
  {
    $this->setPath($record);
    // on the first record written, if the log is new, we should rotate (once per day)
    if (null === $this->mustRotate) {
      $this->mustRotate = !file_exists($this->url);
    }
    if ($this->nextRotation <= $record['datetime']) {
      $this->mustRotate = true;
      $this->close();
    }
    parent::write($record);
  }

  protected function setPath($record){
    preg_match('/\{(.*)\}/',$record['message'],$res,PREG_UNMATCHED_AS_NULL);
    $path = @$res[1].'/';
    $this->setFilenameFormat($path.'sent-{date}','Y-m-d');
  }

  /*public function __invoke($logger){
  foreach($logger->getHandlers() as $handler){

}
}*/
}
