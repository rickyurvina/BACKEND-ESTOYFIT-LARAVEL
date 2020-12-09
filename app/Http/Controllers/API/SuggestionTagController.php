<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuggestionTag;
use App\Models\UserCardToken;
// use App\Http\Controllers\API\stdClass


class SuggestionTagController extends Controller
{
  public function get_suggestion_tag()
  {
    $suggestion_tag = new SuggestionTag;
    $response = $suggestion_tag->get_suggestion_tags();

    return response()->json([
      "data" => $response,
    ]);
  }

  public function get_test(Request $request)
  {
    $suggestion_tag = new SuggestionTag;
    $text = $request->input('text');
    $response = $suggestion_tag->get_test($text);

    return response()->json([
      "data" => $response,
    ]);
  }

  public function delete_card(Request $request)
  {
    $url = 'https://cloud.abitmedia.com/api/payments/delete-card?token=8ac7a4a075695ff701756cbd4acc0ce6';
    $headers = array(
      "Content-Type: application/x-www-form-urlencoded",
      "Postman-Token: 3724770d-a4c7-4330-97dc-6d66237dbc19",
      "cache-control: no-cache",
      sprintf('Authorization: Bearer %s', '2y-13-tx-zsjtggeehkmygjbtsf-51z5-armmnw-ihbuspjufwubv4vxok6ery7wozao3wmggnxjgyg')
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response = json_decode($response);
      echo '<pre>';
      print_r($response);
      echo '</pre>';
    }
  }

  public function pagomediosApiEjemplos(Request $request)
  {
    $user_card_token = new UserCardToken;

    // $custom_object = json_decode($request->input('customValue'));

    $arr_custom_value = explode(",", $request->input('customValue'));

    $custom_value_object = [
      'user_id' => $arr_custom_value[0],
      'document' => $arr_custom_value[1],
      'cardToken' =>  $request->input('cardToken'),
      'description' => $arr_custom_value[2],
      'amount' => $arr_custom_value[3],
      'amountWithTax' => $arr_custom_value[4],
      'amountWithoutTax' => $arr_custom_value[5],
      'tax' => $arr_custom_value[6],
      'reference' => time(),
    ];

    $objectToDb = [
      'user_id' => $arr_custom_value[0],
      'document' => $custom_value_object['document'],
      'number' => $request->input('number'),
      'type' =>  $request->input('type'),
      'card_number' => $request->input('cardNumber'),
      'card_token' =>  $request->input('cardToken'),
      'authorization_code' =>  $request->input('authorizationCode'),
      'reference' => $request->input('reference'),
      'client_id' => $request->input('clientId'),
      'message' => $request->input('message'),
      'card_brand' => $request->input('cardBrand'),
      'card_holder' => $request->input('cardHolder'),
      'ip_address' => $request->input('ipAddress'),
      'status' => $request->input('status'),
      'object_data_transaction' => json_encode($custom_value_object)
    ];

    $response = $user_card_token->save_card_token($objectToDb);

    $this->execute_payment($custom_value_object);
  }

  public function payment_test(Request $request)
  {

    $url = 'https://cloud.abitmedia.com/api/payments/register-card';
    $headers = array(
      "Content-Type: application/x-www-form-urlencoded",
      "Postman-Token: 3724770d-a4c7-4330-97dc-6d66237dbc19",
      "cache-control: no-cache",
      sprintf('Authorization: Bearer %s', '2y-13-tx-zsjtggeehkmygjbtsf-51z5-armmnw-ihbuspjufwubv4vxok6ery7wozao3wmggnxjgyg')
      // Token test 2y-13-tx-zsjtggeehkmygjbtsf-51z5-armmnw-ihbuspjufwubv4vxok6ery7wozao3wmggnxjgyg
      // Token production 2y-13-zcv0yi7qdspcoa6fwuef9-lu-nedmmdbl65fk1um0lcqwqeggtctsh6m0y4lprftj3bg2r5sc

    );

    $customValueObject = (object) [
      'document' => $request->input('document'),
      'description' => 'Pago prueba API',
      'amount' => $request->input('amount'),
      'amountWithTax' => $request->input('amountWithTax'),
      'amountWithoutTax' => $request->input('amountWithoutTax'),
      'tax' => $request->input('tax'),
      'reference' => time(),
    ];

    // user_id
    // document
    // documentType
    // fullName
    // address
    // mobile
    // email
    // amount
    // amountWithTax
    // amountWithoutTax
    // tax

    // $customValue = '2'.','.'1758808636'.','.'Pago prueba API'.','.'1.12'.','.'1'.','.'0'.','.'0.12'.','.time().'';
    $customValue = $request->input('user_id') . ',' . $request->input('document') . ',' . 'Pago prueba API' . ',' . $request->input('amount') . ',' . $request->input('amountWithTax') . ',' . $request->input('amountWithoutTax') . ',' . $request->input('tax') . ',' . time() . '';

    $data = [
      'companyType' => 'Persona Natural', //Persona Natural, Empresa
      'document' => $request->input('document'),
      'documentType' => $request->input('documentType'), //01 Cédula de identidad, 02 RUC, Pasaporte, 03 ID del exterior
      'fullName' => $request->input('fullName'),
      'address' => $request->input('address'),
      'mobile' => $request->input('mobile'),
      'email' => $request->input('email'),
      'notifyUrl' => 'https://estoyfitstaging.ga/api/api/front/pagomediosApiEjemplos',
      'customValue' => $customValue,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      // echo "cURL Error #:" . $err;
      return response()->json([
        "error" => json_decode($err)
      ]);
    } else {
      $response = json_decode($response);
      return response()->json([
        "data" => $response,
        // "custom" => $laravel_object
      ]);
    }
  }

  public function payment_cards(Request $request)
  {

    $url = 'https://cloud.abitmedia.com/api/payments/cards?document=1758808636';
    $headers = array(
      "Content-Type: application/x-www-form-urlencoded",
      "Postman-Token: 3724770d-a4c7-4330-97dc-6d66237dbc19",
      "cache-control: no-cache",
      sprintf('Authorization: Bearer %s', '2y-13-tx-zsjtggeehkmygjbtsf-51z5-armmnw-ihbuspjufwubv4vxok6ery7wozao3wmggnxjgyg')
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response = json_decode($response);
      echo '<pre>';
      print_r($response);
      echo '</pre>';
    }
  }

  public function payment_pay(Request $request)
  {
    // $data = [
    //   'document' => '1758808636',
    //   'cardToken' => '8ac7a4a175647fbc017566a3c8e02957',
    //   'description' => 'Pago prueba API',
    //   'amount' => 1.12,
    //   'amountWithTax' => 1,
    //   'amountWithoutTax' => 0,
    //   'tax' => 0.12,
    //   'reference' => time(),
    //   // 'generateInvoice' => 1,
    // ];

    $user_card_token = new UserCardToken;
    $card = $user_card_token->get_card_by_id($request->input('card_id'));

    $customValueObject = [
      'document' => $request->input('document'),
      'cardToken' => $card->card_token,
      'description' => 'Pago prueba API',
      'amount' => $request->input('amount'),
      'amountWithTax' => $request->input('amountWithTax'),
      'amountWithoutTax' => $request->input('amountWithoutTax'),
      'tax' => $request->input('tax'),
      'reference' => time(),
    ];


    $this->execute_payment($customValueObject);
  }

  public function execute_payment($data)
  {
    $url = 'https://cloud.abitmedia.com/api/payments/charge';

    // $data = $data;

    // print_r('PAYMENT EXEC');
    // print_r($data);

    $headers = array(
      "Content-Type: application/x-www-form-urlencoded",
      "Postman-Token: 3724770d-a4c7-4330-97dc-6d66237dbc19",
      "cache-control: no-cache",
      sprintf('Authorization: Bearer %s', '2y-13-tx-zsjtggeehkmygjbtsf-51z5-armmnw-ihbuspjufwubv4vxok6ery7wozao3wmggnxjgyg')
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response = json_decode($response);
      echo '<pre>';
      print_r($response);
      echo '</pre>';
    }
  }

  public function get_cards_by_user(Request $request)
  {
    $user_card_token = new UserCardToken;
    $user_id = $request->input('user_id');
    $response = $user_card_token->get_cards_by_user($user_id);

    return response()->json([
      "error" => "",
      "response" => $response
    ]);
  }
}
