<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    protected $tablas = [
        'usuarios', 
        'modulos', 
        'roles', 
        'permisos', 
        'usuarios_roles', 
        'roles_permisos', 
        'usuarios_permisos'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'usuarios', function (Blueprint $table) {
            $table->increments('id');

            $table->string('vc_nombre');
            $table->string('vc_email')->unique();
            $table->string('vc_password', 60);

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modulos', function (Blueprint $table) {
            $table->increments('id');

            $table->string('vc_nombre');
            $table->string('vc_slug')->unique();
            $table->string('vc_descripcion')->nullable();

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('vc_nombre');
            $table->string('vc_slug')->unique();
            $table->string('vc_descripcion')->nullable();
            $table->integer('nu_nivel')->default(1);

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permisos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_modulo')->unsigned()->nullable()->index();

            $table->string('vc_nombre');
            $table->string('vc_slug')->unique();
            $table->string('vc_descripcion')->nullable();

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_modulo')->references('id')->on('modulos')->onDelete('cascade');
        });

        Schema::create('usuarios_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario')->unsigned()->index();
            $table->integer('id_rol')->unsigned()->index();

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('usuarios_permisos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('id_usuario')->unsigned()->index();
            $table->integer('id_permiso')->unsigned()->index();            
            $table->integer('id_rol')->unsigned()->index();            

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_permiso')->references('id')->on('permisos')->onDelete('cascade');
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('roles_permisos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('id_rol')->unsigned()->index();
            $table->integer('id_permiso')->unsigned()->index();

            $table->tinyInteger('sn_activo')->default(1);
            $table->tinyInteger('sn_eliminado')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('id_permiso')->references('id')->on('permisos')->onDelete('cascade');
        });

        // Modificamos los timestamps
        foreach ($this->tablas as $value) {
            Schema::table( $value, function($table)
            {
                //Cambiamos los nombres de las fechas
                $table->renameColumn('created_at', 'dt_registro');
                $table->renameColumn('updated_at', 'dt_editado');
                $table->renameColumn('deleted_at', 'dt_eliminado');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::drop('roles_permisos');
        Schema::drop('usuarios_permisos');
        Schema::drop('usuarios_roles');
        Schema::drop('permissions');
        Schema::drop('modulos');
        Schema::drop('roles');
        Schema::drop('usuarios');
        
    }
}
