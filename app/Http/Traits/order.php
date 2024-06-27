<?php
namespace App\Http\Traits;
trait order
{
    public function add_db_connection($panel)
    {
        config(['database.connection' . $panel->db_name => [
            'driver' => 'mysql',
            'host' => $panel->server,
            'port' => $panel->port,
            'database' => $panel->db_name,
            'username' => $panel->username,
            'password' => $panel->password,
            'charset' => 'utf8',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
        ]]);
    }
    public function set_db_connection($panel)
    {
        $this->add_db_connection($panel);

        try{
            DB::connection($panel->db_name)->getPdo();
        }
        catch (\Exception $e)
        {
            // dd($e)
            return false;
        }
        // get db name om collection currently panel
        $db_name = config('database.connection'.$panel->db_name.'.database');
        return $panel->db_name == $db_name;

    }

}
