<?php
$_kind;
$_range;$_rangeVal=0;
$_term; $_price;
if(!empty($_POST['kind']))switch($_POST['kind'])
{
	case "1":
		$_kind='Односкатный';
		break;
	case "2":
		$_kind='Двускатный';
		break;
	case "3":
		$_kind='Арочный';
		break;
}
else $_kind='Пользователь ничего не выбрал';
if(!empty($_POST['range']))switch($_POST['range'])
{
	case "1":
		$_range='до 15 км';
		$_rangeVal=1900;
		break;
	case "2":
		$_range='15-45 км';
		$_rangeVal=2500;
		break;
	case "3":
		$_range='45 - 75 км';
		$_rangeVal=3500;
		break;
	case "4":
		$_range='75 км и больше';
		$_rangeVal=4700;
		break;
}
else $_range='Пользователь ничего не выбрал';
if(!empty($_POST['term']))switch($_POST['term'])
{
	case "1":
		$_term='Через 1 неделю';
		break;
	case "2":
		$_term='Через 2 недели';
		break;
	case "3":
		$_term='Весной 2017';
		break;
	case "4":
		$_term='Летом 2017 / другое';
		break;
}
else $_term='Пользователь ничего не выбрал';
if (($_POST['hight']*$_POST['weight'])<=15) $_price = $_POST['hight']*$_POST['weight']*4000+$_rangeVal;
elseif (($_POST['hight']*$_POST['weight'])>15 && ($_POST['hight']*$_POST['weight'])<=20) $_price = $_POST['hight']*$_POST['weight']*3500+$_rangeVal;
elseif (($_POST['hight']*$_POST['weight'])>20 && ($_POST['hight']*$_POST['weight'])<=28) $_price = $_POST['hight']*$_POST['weight']*3000+$_rangeVal;
elseif (($_POST['hight']*$_POST['weight'])>28 && ($_POST['hight']*$_POST['weight'])<=40) $_price = $_POST['hight']*$_POST['weight']*2800+$_rangeVal;
else $_price = $_POST['hight']*$_POST['weight']*2500+$_rangeVal;
$idlid=time();
$leads['request']['leads']['add']=array(
  array(
    'name'=>'Навес '.$_POST['hight'].'x'.$_POST['weight'],
    'date_create'=>time(), //optional
    'status_id'=>14261944,
    'price'=>$_price,
    'responsible_user_id'=>1375075,
	'tags'=>'Навесы',
    'custom_fields'=>array(
		//Тип навеса	
		array(
			'id'=>310473,
			'values'=>array( array( 'value'=>$_kind) )
		),
		//Удалённость от МКАД
		array(
			'id'=>338867,
			'values'=>array( array( 'value'=>$_range) )
		),
		//Примерное время установки
		array(
			'id'=>313943,
			'values'=>array( array( 'value'=>$_term) )
		)
    )
  )
);

#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
 
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

$code=(int)$code;
$errors=array(
  301=>'Moved permanently',
  400=>'Bad request',
  401=>'Unauthorized',
  403=>'Forbidden',
  404=>'Not found',
  500=>'Internal server error',
  502=>'Bad gateway',
  503=>'Service unavailable'
);
try
{
  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
  if($code!=200 && $code!=204)
    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
}
catch(Exception $E)
{
  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
}
 
/**
 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 * нам придётся перевести ответ в формат, понятный PHP
 */
$Response=json_decode($out,true);
$Response=$Response['response']['leads']['add'];

$outputt=PHP_EOL;
foreach($Response as $v)
if(is_array($v))
$outputt.=$v['id'].PHP_EOL;
$leadd_id = $v['id'];
return $outputt;
?>