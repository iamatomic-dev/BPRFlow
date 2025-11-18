<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credit_application_details', function (Blueprint $table) {
            $table->renameColumn('pasangan_nama', 'nama_pasangan');
            $table->renameColumn('pasangan_no_ktp', 'no_ktp_pasangan');
            $table->renameColumn('pasangan_alamat_tinggal', 'alamat_tinggal_pasangan');
            $table->renameColumn('pasangan_alamat_ktp', 'alamat_ktp_pasangan');
            $table->renameColumn('pasangan_pekerjaan', 'pekerjaan_pasangan');
            $table->renameColumn('pasangan_email', 'email_pasangan');

            $table->renameColumn('penjamin_nama', 'nama_penjamin');
            $table->renameColumn('penjamin_no_ktp', 'no_ktp_penjamin');
            $table->renameColumn('penjamin_hubungan', 'hubungan_penjamin');
            $table->renameColumn('penjamin_alamat', 'alamat_penjamin');
            $table->renameColumn('penjamin_email', 'email_penjamin');
        });

        Schema::table('credit_application_details', function (Blueprint $table) {
            $table->string('no_hp_pasangan')->nullable()->after('email_pasangan');
            $table->string('no_hp_penjamin')->nullable()->after('email_penjamin');
        });
    }

    public function down(): void
    {
        Schema::table('credit_application_details', function (Blueprint $table) {
            $table->renameColumn('nama_pasangan', 'pasangan_nama');
            $table->renameColumn('no_ktp_pasangan', 'pasangan_no_ktp');
            $table->renameColumn('alamat_tinggal_pasangan', 'pasangan_alamat_tinggal');
            $table->renameColumn('alamat_ktp_pasangan', 'pasangan_alamat_ktp');
            $table->renameColumn('pekerjaan_pasangan', 'pasangan_pekerjaan');
            $table->renameColumn('email_pasangan', 'pasangan_email');

            $table->renameColumn('nama_penjamin', 'penjamin_nama');
            $table->renameColumn('no_ktp_penjamin', 'penjamin_no_ktp');
            $table->renameColumn('hubungan_penjamin', 'penjamin_hubungan');
            $table->renameColumn('alamat_penjamin', 'penjamin_alamat');
            $table->renameColumn('email_penjamin', 'penjamin_email');

            $table->dropColumn(['no_hp_pasangan', 'no_hp_penjamin']);
        });
    }
};
