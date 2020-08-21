<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $paypal_sandbox
 * @property string $paypal_production
 * @property string $paypal_env
 * @property string $beneficiario
 * @property string $banco
 * @property string $clabe
 * @property string $concepto
 * @property string $referencia
 * @property string $envio
 * @property string $mprod
 * @property string $slider
 * @property string $created_at
 * @property string $updated_at
 */
class ConfiguracionModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configuracion';

    /**
     * @var array
     */
    protected $fillable = ['paypal_sandbox', 'paypal_production', 'paypal_env', 'beneficiario', 'banco', 'clabe', 'concepto', 'referencia', 'envio', 'mprod', 'slider', 'created_at', 'updated_at'];

}