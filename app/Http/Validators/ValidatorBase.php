<?php
namespace App\Http\Validators;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

/**
 * ValidatorBase class
 */
abstract class ValidatorBase {
    abstract protected function rules();
    abstract protected function messages();
    abstract protected function attributes();

    /**
     * Base validate function
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(array $input){
        $v = Validator::make($input, $this->rules(), $this->messages(), $this->attributes());
        if($v->fails()){
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'message' => $v->errors()->getMessages(),
            ], 422));
        }
        return $v;
    }
}

?>
