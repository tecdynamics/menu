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
         Schema::table('menu_nodes', function ($table) {

             if (!Schema::hasColumn('menu_nodes', 'icon')) {
                 $table->string('icon')->nullable()->default('')->after('has_child');
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
            Schema::dropColumns('menu_nodes', 'icon');
        }
};
