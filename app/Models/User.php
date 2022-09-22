<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\Welcome;
use \Spatie\WelcomeNotification\ReceivesWelcomeNotification;

use Carbon\Carbon;
use DB;
use Mail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, ReceivesWelcomeNotification;

    // public function sendPasswordResetNotification($token)
    // {
    //     $this->notify(new Welcome($token));
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'job',
        'horario',
        'id_empresa',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company() {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }

    public function fichajes() {
        return $this->hasMany(Fichaje::class);
    }

    public function fichajesBetween($entrada, $salida) {
        return $this->hasMany(Fichaje::class)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])->get();
    }

    public function horas() {
        return $this->hasOne(Horario::class, 'id', 'horario');
    }

    public function porcentajeOlvidados($entrada, $salida) {
        $fichajesPeriodo = Fichaje::where('user_id', $this->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->orderBy('started_at')
        ->select('fichajes.*')->get();
        $fichajesOlvidadosPeriodo = Fichaje::where('user_id', $this->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->orderBy('started_at')
        ->where('forgot', 1)
        ->select('fichajes.*')->get();
        $numero_olvidados = $fichajesOlvidadosPeriodo->count();
        $numero_fichajes = $fichajesPeriodo->count();
        if($numero_olvidados)
            $porcentajeOlvidados = $numero_olvidados*100/$numero_fichajes;
        else
            $porcentajeOlvidados = 0;
        return $porcentajeOlvidados;
    }

    public function mediaDiaria($entrada, $salida) {
        $dias_trabajados = Fichaje::where('user_id', $this->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->groupBy(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))
        ->select(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))
        ->get();
        $numero_dias_trabajados = $dias_trabajados->count();
        $total_minutes_periodo = Fichaje::where('user_id', $this->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->sum('total_time');
        if($numero_dias_trabajados)
            $mediaMinutos = $total_minutes_periodo/$numero_dias_trabajados;
        else
            $mediaMinutos = 0;
        return $mediaMinutos;
    }

    public function diasTrabajados($entrada, $salida) {
        $dias_trabajados = Fichaje::where('user_id', $this->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->groupBy(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))
        ->select(DB::raw("DATE_FORMAT(started_at, '%d-%m-%Y')"))
        ->get();
        $numero_dias_trabajados = $dias_trabajados->count();
        return $numero_dias_trabajados;
    }

    public function numeroFichajes($entrada, $salida) {
        $fichajesPeriodo = Fichaje::where('user_id', $this->id)->whereBetween('started_at', [Carbon::parse($entrada)->startOfDay(), Carbon::parse($salida)->endOfDay()])
        ->orderBy('started_at')
        ->select('fichajes.*')->get();
        $numero_fichajes = $fichajesPeriodo->count();
        return $numero_fichajes;
    }

    public function sendWelcomeMail($user) {
        // Generate a new reset password token
        $token = app('auth.password.broker')->createToken($user);

        // Send email
        Mail::send('emails.welcome', ['user' => $user, 'token' => $token], function ($m) use ($user) {
            $m->from('hello@appsite.com', 'Your App Name');

            $m->to($user->email, $user->name)->subject('Welcome to APP');
        });
    }



    public function sendWelcomeNotification(\Carbon\Carbon $validUntil)
    {
        $this->notify(new Welcome($validUntil));
    }
}
