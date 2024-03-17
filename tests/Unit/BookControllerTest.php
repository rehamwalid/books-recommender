<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test reading pages api
     */
    public function test_store_read_pages(): void
    {
        $books = Book::factory()->count(2)->create();
        $users = User::factory()->count(3)->create();
        $data=[
           "user_id" => $users->first()->id,
           "book_id" => $books->first()->id,
           "start_page" => 1,
            "end_page" => 10
        ];

        $this
            ->post('api/v1/book',$data)
            ->assertOk()
            ->assertSee($books->first()->id);

        $this->assertDatabaseHas('books', [
            'id'    => $books->first()->id,
            'num_read_pages'   => 10,
        ]);
    }

    /**
     * test reading pages with bad request
     */
    public function test_store_read_pages_bad_request(): void
    {
        $books = Book::factory()->count(2)->create();
        $users = User::factory()->count(3)->create();
        $data=[
            "user_id" => 12,
            "book_id" => $books->first()->id,
            "start_page" => 1,
            "end_page" => 10
        ];

       $response = $this->postJson('api/v1/book',$data)
            ->assertStatus(422);
        $response->assertSee('user_id');
    }


    /**
     * test calculating num of unique pages read
     */
    public function test_num_of_pages_method(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $book->users()->attach($user,[
            'start_page' => 40,
            'end_page' => 50
        ]);

        $book->users()->attach($user,[
            'start_page' => 45,
            'end_page' => 70
        ]);

        $book->users()->attach($user,[
            'start_page' => 35,
            'end_page' => 60
        ]);

        $book->numOfPages();
        $this->assertDatabaseHas('books', [
            'id'    => $book->id,
            'num_read_pages'   => 36,
        ]);
    }

    /**
     * test get recommended books api
     */
    public function test_get_recommended_books(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book1 = Book::factory()->hasAttached($user1,[
            'start_page' => 1,
            'end_page' => 10
        ])->create();

        $book2 = Book::factory()->hasAttached($user1->first(),[
            'start_page' => 10,
            'end_page' => 20
        ])->create();

        $book3 = Book::factory()->hasAttached($user1->first(),[
            'start_page' => 1,
            'end_page' => 30
        ])->create();

        $book1->users()->attach($user2,[
            'start_page' => 40,
            'end_page' => 50
        ]);

        $book2->users()->attach($user2,[
            'start_page' => 5,
            'end_page' => 15
        ]);
        //Calculate number of pages read
        $book1->numOfPages();
        $book2->numOfPages();
        $book3->numOfPages();

        $response = $this->get('api/v1/book')
            ->assertOk();
        $response->assertSeeInOrder([$book3->id ,$book1->id,$book2->id]);
    }



}
