<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(50)->make();
        User::insert($users->toArray());
        $user = User::find(1);
        $user->name = 'Jake';
        $user->email = '231005467@qq.com';
        $user->password = 'ke0520836';
        $user->is_admin = true;
        $user->save();
    }
}
