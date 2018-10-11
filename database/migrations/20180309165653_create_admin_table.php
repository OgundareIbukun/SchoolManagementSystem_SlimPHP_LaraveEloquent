<?php

use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;

class CreateAdminTable extends BaseMigration
{

    public function up()
    {
        //

        $this->schema->create('admins', function (Blueprint $table) {
            $table->increments('adminId', 15);
            $table->string('username');
            $table->string('email');
            $table->string('token');
            $table->string('pswd');
            $table->timestamps();
            $table->unique('username', 'email');
   
        });
    }

    public function down()
    {
        //

        $this->schema->dropIfExists('admins');
    }

}
