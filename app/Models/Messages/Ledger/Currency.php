<?php

namespace App\Models\Messages\Ledger;


use App\Exceptions\Breaker;
use App\Models\Messages\Message;
use Illuminate\Http\Request;

class Currency extends Message
{
    public string $code;
    public int $decimals;
    public ?string $revision;
    public ?string $toCode = null;

    /**
     * @inheritdoc
     */
    public static function fromRequest(array $data, int $opFlag)
    : Currency {
        $errors = [];
        $result = new static();

        if (($data['code'] ?? false)) {
            $result->code = strtoupper($data['code']);
        }

        if (
            !($opFlag & self::OP_DELETE)
            && isset($data['decimals'])
            && is_numeric($data['decimals'])
        ) {
            $result->decimals = (int)$data['decimals'];
        }
        if ($opFlag & self::OP_UPDATE) {
            if (isset($data['revision'])) {
                $result->revision = $data['revision'];
            }
            if (isset($data['toCode'])) {
                $result->toCode = strtoupper($data['toCode']);
            }
        }
        if ($opFlag & self::FN_VALIDATE) {
            $result->validate($opFlag);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function validate(int $opFlag): self
    {
        $errors = [];
        if (!isset($this->code)) {
            $errors[] = __('the code property is required');
        }

        if (!($opFlag & (self::OP_DELETE | self::OP_GET))) {
            if (!isset($this->decimals)) {
                $errors[] = __('a numeric decimals property is required');
            }
        }
        if ($opFlag & self::OP_UPDATE) {
            if (!isset($this->revision)) {
                $errors[] = __('the revision property is required');
            }
        }
        if (count($errors) !== 0) {
            throw Breaker::withCode(Breaker::BAD_REQUEST, $errors);
        }

        return $this;
    }
}
