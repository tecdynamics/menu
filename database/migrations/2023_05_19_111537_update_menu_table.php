<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Tec\Ecommerce\Models\Product;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
     public  function up() {
         Schema::table('menus', function ($table) {

             if (!Schema::hasColumn('menus', 'image')) {
                 $table->string('image')->nullable()->default('')->after('status');
             }
             if (!Schema::hasColumn('menus', 'template')) {
                 $table->string('template')->nullable()->default('default')->after('image');
             }
         });

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public
        function down() {
            Schema::dropColumns('menu_nodes', 'image');
            Schema::dropColumns('menu_nodes', 'template');
        }
};
