<?php

namespace App\Models;


use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'google_id',
        'phone',
        'address',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     *
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts() : array {
        return
        [
            'email_veryfied_at' => 'datetime',
            'password' => 'hashed',
        ];
   }

   public function cart()
    {
        return $this->hasOne(Cart::class);
   }
   public function wishlists()
{
    return $this->belongsToMany(Product::class, 'wishlists')
                ->withTimestamps();
}

   public function orders()
    {
        return $this->hasMany(Order::class);
   }

      public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function hasInWishlist(Product $product)
{
    return $this->wishlists()->where('product_id', $product->id)->exists();
}
        public function getAvatarUrlAttribute() : string {
       if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
        return asset('storage/' . $this->avatar);
    }

    if (str_starts_with($this->avatar ?? '', 'http')) {
        return $this->avatar;
    }

    $hash = md5(strtolower(trim($this->email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
}


public function getInitialsAttribute(): string
{
    $words = explode(' ', $this->name);
    $initials = '';

    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }

    return substr($initials, 0, 2);
}
            }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */


