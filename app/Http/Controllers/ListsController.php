<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;

use GuzzleHttp\Client as GClient;

class ListsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'GET',
                'path' => '/lists'
            ], 400, ['content-type' => 'application/json']);
        }

        try {

            $client = new GClient();
            $res = $client->get($apiPath . 'lists', [
                'auth' => [$apiUser, $apiKey]
            ]);

            $data = json_decode($res->getBody());

            return Response::create([
                'status' => 'API-' . $res->getStatusCode(),
                'method' => 'GET',
                'path' => '/lists',
                'resource-data' => [
                    'lists' => $data->lists,
                    'count' => count($data->lists)
                ]
            ], 200, ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Could load lists resource',
                'method' => 'GET',
                'path' => '/lists'
            ], 400, ['content-type' => 'application/json']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return Response::create([
            'status' => 'API-404',
            'message' => 'Resource not available',
            'method' => 'GET',
            'path' => '/lists/create'
        ], 404, ['content-type' => 'application/json']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'POST',
                'path' => '/lists'
            ], 400, ['content-type' => 'application/json']);
        }

        $messages = [];
        try {
            $this->validate($request, $this->getValidationSchema(), $messages);

            $client = new GClient();
            $res = $client->post($apiPath . 'lists', [
                'auth' => [$apiUser, $apiKey],
                'content-type' => 'application/json',
                'json' => $this->extractInputData($request)
            ]);

            $resData = json_decode($res->getBody()->getContents());

            return Response::create([
                'status' => 'API-200',
                'path' => '/lists',
                'method' => 'POST',
                'resource-uri' => '/lists/' . $resData->id
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Could not create resource',
                'method' => 'POST',
                'path' => '/lists'
            ], 400, ['content-type' => 'application/json']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'GET',
                'path' => '/lists'
            ], 400, ['content-type' => 'application/json']);
        }

        try {
            $client = new GClient();
            $res = $client->get($apiPath . 'lists/' . $id, [
                'auth' => [$apiUser, $apiKey]
            ]);

            $data = json_decode($res->getBody());

            if ($res->getStatusCode() != 200) {
                throw new \Exception();
            }

            unset($data->_links);

            return Response::create([
                'status' => 'API-' . $res->getStatusCode(),
                'path' => '/lists/' . $id,
                'method' => 'GET',
                'resource' => $data
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-404',
                'message' => 'Resource not found',
                'method' => 'POST',
                'path' => '/lists/' . $id
            ], 404, ['content-type' => 'application/json']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return Response::create([
            'status' => 'API-404',
            'message' => 'Resource not available',
            'method' => 'GET',
            'path' => '/lists/' . $id . '/edit'
        ], 404, ['content-type' => 'application/json']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'PATCH',
                'path' => '/lists/' . $id
            ], 400, ['content-type' => 'application/json']);
        }

        $messages = [];
        try {
            $this->validate($request, $this->getValidationSchema(), $messages);

            $client = new GClient();
            $res = $client->patch($apiPath . 'lists/' . $id, [
                'auth' => [$apiUser, $apiKey],
                'content-type' => 'application/json',
                'json' => $this->extractInputData($request)
            ]);

            $resData = json_decode($res->getBody()->getContents());

            return Response::create([
                'status' => 'API-200',
                'path' => '/lists',
                'method' => 'PATCH',
                'resource-uri' => '/lists/' . $resData->id,
                'resource' => $resData
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Could not patch resource',
                'method' => 'PATCH',
                'path' => '/lists/' . $id
            ], 400, ['content-type' => 'application/json']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'GET',
                'path' => '/lists'
            ], 400, ['content-type' => 'application/json']);
        }

        try {
            $client = new GClient();
            $client->delete($apiPath . 'lists/' . $id, [
                'auth' => [$apiUser, $apiKey]
            ]);

            // Change '200' to '204' if no JSON response is desired
            return Response::create([
                'status' => 'API-204',
                'message' => 'Resource has been deleted',
                'method' => 'DELETE',
                'path' => '/lists/' . $id
            ], 200, ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Resource not available',
                'method' => 'DELETE',
                'path' => '/lists/' . $id,
            ], 400, ['content-type' => 'application/json']);
        }
    }

    protected function getValidationSchema() {
        return [
            'name' => 'required',
                'contact_company' => 'required',
                'contact_address1' => 'required',
                'contact_address2' => '',
                'contact_city' => 'required',
                'contact_state' => 'required',
                'contact_zip' => 'required',
                'contact_country' => 'required',
                'contact_phone' => '',
            'permission_reminder' => 'required',
            'use_archive_bar' => '',
                'campaign_defaults_from_name' => 'required',
                'campaign_defaults_from_email' => 'required',
                'campaign_defaults_subject' => 'required',
                'campaign_defaults_language' => 'required',
            'notify_on_subscribe' => '',
            'notify_on_unsubscribe' => '',
            'email_type_option' => 'required',
            'visibility' => ''
        ];
    }

    protected function extractInputData(Request $request) {
        return $this->filterEmptyInput([
            'name' => $request->get('name', ''),
            'contact' => [
                'company'   => $request->get('contact_company', ''),
                'address1'  => $request->get('contact_address1', ''),
                'address2'  => $request->get('contact_address2', ''),
                'city'      => $request->get('contact_city', ''),
                'state'     => $request->get('contact_state', ''),
                'zip'       => $request->get('contact_zip', ''),
                'country'   => $request->get('contact_country', ''),
                'phone'     => $request->get('contact_phone', ''),
            ],
            'permission_reminder' => $request->get('permission_reminder', ''),
            'use_archive_bar' => $request->get('use_archive_bar', '') == true,
            'campaign_defaults' => [
                'from_name'     => $request->get('campaign_defaults_from_name', ''),
                'from_email'    => $request->get('campaign_defaults_from_email', ''),
                'subject'       => $request->get('campaign_defaults_subject', ''),
                'language'      => $request->get('campaign_defaults_language', ''),
            ],
            'notify_on_subscribe' => $request->get('notify_on_subscribe', ''),
            'notify_on_unsubscribe' => $request->get('notify_on_unsubscribe', ''),
            'email_type_option' => $request->get('email_type_option', '') == true,
            'visibility' => $request->get('visibility', '')
        ]);
    }

    protected function filterEmptyInput(array $data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $arr = $this->filterEmptyInput($value);
                if (empty($arr)) {
                    unset($data[$key]);
                } else {
                    $data[$key] = $arr;
                }
            } else if (!is_bool($value) && empty($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

}
