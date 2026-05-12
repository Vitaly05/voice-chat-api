<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ChatStatuses;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create( 'chats', function ( Blueprint $table ) {
            $table->id();

            $table->enum( 'status', array_column( ChatStatuses::cases(), 'value' ) )
                ->default( ChatStatuses::REQUESTED->value );

            $table->unsignedBigInteger( 'creator_id' );
            $table->foreign( 'creator_id' )
                ->references( 'id' )
                ->on( 'users' )
                ->cascadeOnDelete();

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists( 'chats' );
    }
};
