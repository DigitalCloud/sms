<?php


namespace Digitalcloud\SMS;


use Illuminate\Notifications\Notifiable;

class ProviderResponse
{
    private $success;
    private $provider;
    private $code;
    private $mobile;
    private $message;
    private $response;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }


    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public static function make()
    {
        return new self();
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }


    public function setSuccess(bool $success)
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    public function setProvider(string $provider)
    {
        $this->provider = $provider;
        return $this;
    }

    public function log(Notifiable $notifiable, string $notification)
    {
        $notifiable->smsLogs()->create([
            'notification' => $notification,
            'provider' => $this->getProvider(),
            'mobile' => $this->getMobile(),
            'message' => $this->getMessage(),
            'response' => $this->getResponse(),
            'success' => $this->getSuccess(),
            'response_code' => $this->getCode(),
        ]);
    }

    public function throw()
    {
        if ($this->success) {
            return;
        }

        throw new ProviderException($this->getResponse(), $this->getCode());
    }
}