<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'address',
        'city',
        'state',
        'zip_code',
        'verification_token',
        'verification_expire_date'
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

    public function wproducts(){
        return $this->belongsToMany(Product::class,'wishlist','user_id','product_id')->with('images');
    }

    public function cproducts(){
        return $this->belongsToMany(Product::class,'cart','user_id','product_id')->with('images')->withPivot('quantity');
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function isBought($id){
        $orids = auth()->user()->orders()->pluck('id')->toArray();
        return DB::table('order_product')->whereIn('order_id',$orids)->where('product_id',$id)->exists();
    }
    public function roles(){
        return $this->belongsToMany(Role::class,'user_role');
    }
    public function role(){
        $base = DB::table('user_role')->where('user_id',$this->id)->first();
        if($base){
            $role = Role::where('id',$base->role_id)->first();
            return $role->name;
        }else{
            return 'NULL';
        }
        
    }
    public function isAdmin(){
        return $this->role() === 'admin';
    }
    public function isModerator(){
        return $this->role() === 'moderator';
    }
}
