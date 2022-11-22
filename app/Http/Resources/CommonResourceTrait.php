<?php

namespace App\Http\Resources;

trait CommonResourceTrait
{
    /**
     * 带上提示信息.
     *
     * @param $message
     *
     * @return BaseResourceCollection
     */
    public function withMessage($message): BaseResourceCollection
    {
        $this->with['message'] = $message;

        return $this;
    }
}
