<?php

namespace zjsy\CaptchaLumen5;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

/**
 * Class CaptchaController
 * @package Mews\Captcha
 */
class LumenCaptchaController extends Controller
{

    /**
     * getCaptcha
     *
     * @author guoxiang
     * @date   2018/5/30
     * @param \zjsy\CaptchaLumen5\Facades\Captcha $captcha
     * @param string $type
     * @param $captchaId
     *
     * @return mixed
     */

    public function getCaptcha(\zjsy\CaptchaLumen5\Captcha $captcha, $type = 'default', $captchaId)
    {
         $captcha->createById($type, $captchaId)->send();
    }

    /**
     * get CAPTCHA getCaptchaInfo API
     * @param Request $request
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCaptchaInfo(Request $request, $type = 'default')
    {
        $urlDomain = $request->getSchemeAndHttpHost();
        $captchaUuid = generate_uuid();
        $captchaData = [
            'captchaUrl'=>$urlDomain.'/captcha/'.$type.'/'.$captchaUuid,
            'captchaUuid'=>(string)$captchaUuid
        ];
        return response()->json($captchaData);
    }
}
