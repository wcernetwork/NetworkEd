<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Eloquent::unguard();

		$this->call('RoleTableSeeder');
		$this->call('UserTableSeeder');
		$this->call('PostTypeTableSeeder');
	}

}

class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();

		User::create(
			array(
				'email' => 'admin@email.com',
				'password' => Hash::make('password'),
				'role_id' => 1,
				'first_name' => 'Admin',
				'last_name' => 'User',
				'city' => 'City',
				'state' => 'State',
				'zip' => '99999',
				'posts' => 0,
				'organization' => 'Organization',
				'bio' => 'This is the admin account',
				'role_title' => 'Administrator',
				'photo' => 'photo.png',
				'background_image' => '',
			));
	}
}

class RoleTableSeeder extends Seeder {

	public function run()
	{
		DB::table('roles')->delete();

		DB::table('roles')->insert(
			array(
				'id' => '1',
				'name' => 'Admin',
			));

		DB::table('roles')->insert(
			array(
				'id' => '2',
				'name' => 'Moderator',
			));

		DB::table('roles')->insert(
			array(
				'id' => '3',
				'name' => 'User',
			));
	}
}
		

class PostTypeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('post_types')->delete();

		DB::table('post_types')->insert(
			array(
				'id' => '1',
				'name' => 'Person',
				'icon_file' => 'person',
			));

		DB::table('post_types')->insert(
			array(
				'id' => '2',
				'name' => 'Place',
				'icon_file' => 'place',
			));

		DB::table('post_types')->insert(
			array(
				'id' => '3',
				'name' => 'Event',
				'icon_file' => 'event',
			));

		DB::table('post_types')->insert(
			array(
				'id' => '4',
				'name' => 'Project',
				'icon_file' => 'project',
			));

		DB::table('post_types')->insert(
			array(
				'id' => '5',
				'name' => 'Network Event',
				'icon_file' => 'network_event',
			));
	}

}