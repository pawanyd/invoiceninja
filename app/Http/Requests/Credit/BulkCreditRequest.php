<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2022. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://www.elastic.co/licensing/elastic-license
 */

namespace App\Http\Requests\Credit;

use App\Utils\Traits\MakesHash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkCreditRequest extends FormRequest
{
    use MakesHash;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => ['required','bail','array',Rule::exists('credits','id')->where('company_id', auth()->user()->company()->id)],
            'action' => 'required|bail|in:archive,restore,delete,email,bulk_download,bulk_print,mark_paid,clone_to_credit,history,mark_sent,download,send_email'
        ];
    }

    public function prepareForValidation()
    {
        $input = $this->all();

        if(isset($input['ids']))
            $input['ids'] = $this->transformKeys($input['ids']);

        $this->replace($input);
    }

}
