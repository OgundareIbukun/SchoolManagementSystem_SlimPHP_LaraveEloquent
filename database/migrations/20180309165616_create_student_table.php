<?php

use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;

class CreateStudentTable extends BaseMigration
{

    public function up()
    {
        //

        $this->schema->create('students', function (Blueprint $table) {
            $table->increments('student_id', 15);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->smallInteger('role');
            $table->string('password');
            $table->text('token');
            $table->string('class');
            $table->string('class_arm');
            $table->string('last_login');
            $table->string('gender');
            $table->string('ethnicity');
            $table->binary('student_photo');
            $table->string('social_security');
            $table->date('birthdate');
            $table->string('language');
            $table->string('physician_name');
            $table->string('physician_hospital');
            $table->string('estimated_grad_date');
            $table->string('alt_id');
            $table->string('physician_email');
            $table->string('physician_phone2');
            $table->string('student_address');
            $table->string('student_address_state');
            $table->string('sch_drop_off');
            $table->string('sch_pick_off');
            $table->string('bus_no');
            $table->string('prim_con_rel');
            $table->string('prim_con_state');
            $table->string('prim_con_f_name');
            $table->string('prim_con_o_name');
            $table->string('prim_con_phone1');
            $table->string('prim_con_phone2');
            $table->string('prim_con_email');
            $table->string('sec_con_rel');
            $table->string('sec_con_f_name');
            $table->string('sec_con_o_name');
            $table->string('sec_con_address');
            $table->string('sec_con_state');
            $table->string('sec_con_phone1');
            $table->string('sec_con_phone2');
            $table->timestamps();
          
            // primary key('student_id');
            // key 'name'('last_name', 'first_name', 'middel');
        });
    }
    
    public function down()
    {
        //

        $this->schema->dropIfExists('student');
    }

}
