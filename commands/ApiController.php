<?php

namespace app\commands;

use app\models\Street;
use yii\console\Controller;
use yii\httpclient\Client;
use app\models\City;

class ApiController extends Controller
{
    private $secret = '854E982F9BE2ADD7CCD1E79B86FD3';
    private $url = 'https://digital.kt.ua';
    private $actionCity = 'api/test/cities';
    private $actionStreet = 'api/test/streets';

    private $apiUrl = 'https://jsonplaceholder.typicode.com';
    private $actionPhoto = 'photos';
    private $actionUser = 'user';
    private $actionParam = 10;

    public $start ;

    public function beforeAction($action)
    {
        $this->start = microtime(true);
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function afterAction($action, $result)
    {
        echo 'time = ';
        echo  microtime(true) - $this->start.' c';
        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        //$this->getCity();
    }

    public function actionTest()
    {
        $client = new Client(['baseUrl' => $this->apiUrl]);
        $response = $client->get($this->actionPhoto)->send();

        echo $response;
    }
    
    public function actionTestUser()
    {
        $client = new Client(['baseUrl' => $this->apiUrl]);
        $response = $client->get($this->actionUser)->send();

        echo $response;
    }

    private function getCity()
    {
        $client = new Client(['baseUrl' => $this->url]);
        $cityResponse = $client->get($this->actionCity)
            ->addHeaders(['secret-token' => $this->secret, 'content-type' => 'application/json; charset=UTF-8'])->send();

        if ($cityResponse->isOk) {
            foreach($cityResponse->data as $value){
                if($cityId = $this->addCity($value['name'], $value['ref'])){
                    $this->getStreet($cityId, $value['ref']);
                }
            }
        }
    }

    private function getStreet($cityId, $cityRef)
    {
        $client = new Client(['baseUrl' => $this->url]);
        $streetResponse = $client->get($this->actionStreet)
            ->addHeaders(['secret-token' => $this->secret, 'content-type' => 'application/json; charset=UTF-8'])
            ->setData(['city_ref' => $cityRef])->send();

        if($streetResponse->isOk) {
            foreach($streetResponse->data as $value){
                $this->addStreet($cityId, $value['name'], $value['ref']);
            }
        }
    }

    private function addCity($name, $ref)
    {
        $model = City::findModel($ref);
        if($model){
            $model->name = $name;
            if($model->save())
                return $model->primaryKey;
            else
                return false;
        }
        else{
            $model = new City();
            $model->ref = $ref;
            $model->name = $name;
            if($model->save())
                return $model->primaryKey;
            else
                return false;
        }
    }

    private function addStreet($cityId, $name, $ref)
    {
        $model = Street::findModel($ref);
        if($model){
            $model->name = $name;
            $model->save();
        }
        else{
            $model = new Street();
            $model->ref = $ref;
            $model->name = $name;
            $model->city_id = $cityId;
            $model->save();
        }
    }

}
