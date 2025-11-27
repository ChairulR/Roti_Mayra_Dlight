    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('breads', function (Blueprint $table) {
            // Menambahkan kolom boolean 'is_promoted' dengan nilai default FALSE
            $table->boolean('is_promoted')->default(false)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('breads', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('is_promoted');
        });
    }
};
