<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 08.02.2018
 * Time: 7:21
 */

namespace Manager;

/*spl_autoload_register(function ($class_name) {
    require_once "app/controllers/Abstract_factory_for_Validator/factories/".$class_name.'.php';
}); Автозагрузка классов почему-то не прошла. Вывод ощибки почему-то говорил про ошибку, которая вознкала при
наличии пространства имен.
Все файлы подгружаются из корня директории из-за .htaccess файла который все запросы перекидывает на index.php
*/
require_once 'app/controllers/Abstract_factory_for_Validator/factories/EmailValidator.php';
require_once 'app/controllers/Abstract_factory_for_Validator/factories/PassValidator.php';
require_once 'app/controllers/Abstract_factory_for_Validator/factories/TextValidator.php';

use Email;
use Pass;
use Text;

class FactoryManager{

    /*
     * Попытка создать композицию объектов валидаторов
     */
    private $emailValidator = null;
    private $passValidator = null;
    private $textValidator = null;

    private static $instance = null;

    private function __construct(){}

    private function __clone(){}

    /**
     * Создает единственный экземпляр менеджера, который отвечает за создание подходящей фабрики
     * */
    public static  function getInstance()
    {
        if(!self::$instance){
            return  self::$instance = new FactoryManager();
        }
        else
            return self::$instance;
    }

    /*
     * @method
     */
    public function CreateFactory(array $params = null)
    {
        if($params){
            switch($params['type']){
                case 'email':
                    $this->emailValidator = new Email\EmailValidator();
                    return $this->emailValidator->CheckData($params["data"], $params["pattern"], $params);
                    break;
                case 'pass':
                    $this->passValidator = new Pass\PassValidator();
                    return $this->passValidator->CheckData($params['data'], $params["pattern"], $params);
                    break;
                case "text":
                    $this->textValidator = new Text\TextValidator();
                    return $this->textValidator->CheckData($params['data'], $params["pattern"], $params);
                    break;

                default:
                    break;
            }
        }
        return 0;
    }
} 