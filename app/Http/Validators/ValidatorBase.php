<?php
namespace App\Http\Validators;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
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

        Validator::extend('unique_deleted_at_null', function($attribute, $value, $parameters, $validator){
            $check = DB::table($parameters[0])->where($parameters[1] ?? $attribute, $value)->whereNull('deleted_at')->first();
            if(!empty($check)){
                $validator->addReplacer('unique_deleted_at_null', function($message, $attribute, $rule, $parameters){
                    return str_replace(':attribute', $attribute, $message);
                });
                return false;
            }
            return true;
        });

        Validator::extend('check_action_sms', function($attribute, $value, $parameters, $validator){
            if(empty(ACTION_SMS[$value])){
                $validator->addReplacer('check_action_sms', function($message, $attribute, $rule, $parameters){
                    return str_replace(':attribute', $attribute, $message);
                });
                return false;
            }
            return true;
        });

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
