<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;

use GuzzleHttp\Client as GClient;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $routeId = Route::current()->listid;
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'GET',
                'path' => sprintf('/api/lists/%s/members', $routeId)
            ], 400, ['content-type' => 'application/json']);
        }

        try {

            $client = new GClient();
            $res = $client->get(sprintf('%slists/%s/members', $apiPath, $routeId), [
                'auth' => [$apiUser, $apiKey]
            ]);

            $data = json_decode($res->getBody());

            return Response::create([
                'status' => 'API-' . $res->getStatusCode(),
                'method' => 'GET',
                'path' => sprintf('/api/lists/%s/members', $routeId),
                'resource-data' => [
                    'list_id' => $routeId,
                    'members' => $data->members,
                    'count' => count($data->members)
                ]
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Could load list members resource',
                'method' => 'GET',
                'path' => sprintf('/api/lists/%s/members', $routeId)
            ], 400, ['content-type' => 'application/json']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $routeId = Route::current()->listid;
        return Response::create([
            'status' => 'API-404',
            'message' => 'Resource not available',
            'method' => 'GET',
            'path' => sprintf('/api/lists/%s/members/create', $routeId)
        ], 404, ['content-type' => 'application/json']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $routeId = Route::current()->listid;
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'POST',
                'path' => sprintf('/api/lists/%s/members', $routeId)
            ], 400, ['content-type' => 'application/json']);
        }

        $messages = [];
        try {
            $this->validate($request, $this->getValidationSchema(), $messages);

            $client = new GClient();
            $res = $client->post(sprintf('%slists/%s/members', $apiPath, $routeId), [
                'auth' => [$apiUser, $apiKey],
                'content-type' => 'application/json',
                'json' => $this->extractInputData($request)
            ]);

            $resData = json_decode($res->getBody()->getContents());

            return Response::create([
                'status' => 'API-200',
                'path' => sprintf('/api/lists/%s/members', $routeId),
                'method' => 'POST',
                'resource-uri' => sprintf('/api/lists/%s/members/%s', $routeId, $resData->id)
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Could not create resource',
                'method' => 'POST',
                'path' => sprintf('/api/lists/%s/members', $routeId)
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
        $id = Route::current()->member;
        $routeId = Route::current()->listid;
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'GET',
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id)
            ], 400, ['content-type' => 'application/json']);
        }

        try {
            $client = new GClient();
            $res = $client->get(sprintf('%slists/%s/members/%s', $apiPath, $routeId, $id), [
                'auth' => [$apiUser, $apiKey]
            ]);

            $data = json_decode($res->getBody());

            if ($res->getStatusCode() != 200) {
                throw new \Exception();
            }

            unset($data->_links, $data->list_id);

            return Response::create([
                'status' => 'API-' . $res->getStatusCode(),
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id),
                'method' => 'GET',
                'resource' => $data
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-404',
                'message' => 'Resource not found',
                'method' => 'GET',
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id),
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
        $routeId = Route::current()->listid;
        return Response::create([
            'status' => 'API-404',
            'message' => 'Resource not available',
            'method' => 'GET',
            'path' => sprintf('/api/lists/%s/members/edit', $routeId)
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
        $id = Route::current()->member;
        $routeId = Route::current()->listid;
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'PATCH',
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id)
            ], 400, ['content-type' => 'application/json']);
        }

        $messages = [];
        try {
            $this->validate($request, $this->getValidationSchema(), $messages);

            $client = new GClient();
            $res = $client->patch(sprintf('%slists/%s/members/%s', $apiPath, $routeId, $id), [
                'auth' => [$apiUser, $apiKey],
                'content-type' => 'application/json',
                'json' => $this->extractInputData($request)
            ]);

            $resData = json_decode($res->getBody()->getContents());
            $id = $resData->id;
            unset($resData->_links, $resData->list_id);

            return Response::create([
                'status' => 'API-200',
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id),
                'method' => 'PATCH',
                'resource-uri' => sprintf('/api/lists/%s/members/%s', $routeId, $resData->id),
                'resource' => $resData
            ], $res->getStatusCode(), ['content-type' => 'application/json']);
        } catch (\Exception $ex) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Could not create resource',
                'method' => 'PATCH',
                'path' => sprintf('/api/lists/%s/members', $routeId)
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
        $id = Route::current()->member;
        $routeId = Route::current()->listid;
        $apiKey = config('mc.mc_api_key');
        $apiUser = config('mc.mc_api_user');
        $apiPath = config('mc.mc_api_path');

        if (!$apiKey || !$apiUser) {
            return Response::create([
                'status' => 'API-400',
                'message' => 'Invalid API key or API user, check app settings',
                'method' => 'DELETE',
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id)
            ], 400, ['content-type' => 'application/json']);
        }

        try {
            $client = new GClient();
            $client->delete(sprintf('%slists/%s/members/%s', $apiPath, $routeId, $id), [
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
                'path' => sprintf('/api/lists/%s/members/%s', $routeId, $id),
            ], 400, ['content-type' => 'application/json']);
        }
    }

    protected function getValidationSchema() {
        return [
            'email_address' => 'required',
            'email_type' => '',
            'status' => 'required',
            'interests' => '',
            //'interests_title' => 'required',
            'interests_title' => '',
            'interests_display_order' => '',
            //'interests_type' => 'required',
            'interests_type' => '',
            'language' => '',
            'vip' => '',
            'location' => '',
            'location_latitude' => '',
            'location_longitude' => '',
            'ip_signup' => '',
            'timestamp_signup' => '',
            'ip_opt' => '',
            'timestamp_opt' => '',
            'merge_fields' => '',
            'merge_fields_tag' => '',
            //'merge_fields_name' => 'required',
            'merge_fields_name' => '',
            'merge_fields_type' => '',
            'merge_fields_required' => '',
            'merge_fields_default_value' => '',
            'merge_fields_public' => '',
            'merge_fields_display_order' => '',
            'merge_fields_options_default_country' => '',
            'merge_fields_options_phone_format' => '',
            'merge_fields_options_date_format' => '',
            'merge_fields_options_choices' => '',
            'merge_fields_options_size' => '',
            'merge_fields_help_text' => ''
        ];
    }

    protected function extractInputData(Request $request) {
        return $this->filterEmptyInput([
            'email_address' => $request->get('email_address', ''),
            'email_type' => $request->get('email_type', ''),
            'status' => $request->get('status', ''),
            'merge_fields' => [
                'tag' => $request->get('tag', ''),
                'name' => $request->get('name', ''),
                'type' => $request->get('type', ''),
                'required' => $request->get('required', ''),
                'default_value' => $request->get('default_value', ''),
                'public' => $request->get('public', ''),
                'display_order' => $request->get('display_order', ''),
                'options' => [
                    'default_country' => $request->get('default_country', ''),
                    'phone_format' => $request->get('phone_format', ''),
                    'date_format' => $request->get('date_format', ''),
                    'choices' => $request->get('choices', ''),
                    'size' => $request->get('size', ''),
                ],
                'help_text' => $request->get('help_text', '')
            ],
            'interests' => [
                'title' => $request->get('title', ''),
                'display_order' => $request->get('display_order', ''),
                'type' => $request->get('type', ''),
            ],
            'language' => $request->get('language', ''),
            'vip' => $request->get('vip', '') == true,
            'location' => [
                'latitude' => $request->get('latitude', ''),
                'longitude' => $request->get('longitude', '')
            ],
            'ip_signup' => $request->get('ip_signup', ''),
            'timestamp_signup' => $request->get('timestamp_signup', ''),
            'ip_opt' => $request->get('ip_opt', ''),
            'timestamp_opt' => $request->get('timestamp_opt', '')
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
