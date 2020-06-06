<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Book;

class BookManagmentTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $response = $this->post('/books', [
            'title'  => 'Cool Book Title',
            'author' => 'Mateo',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', [
            'title'  => '',
            'author' => 'Mateo',
        ]);


        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        $response = $this->post('/books', [
            'title'  => 'Cool title',
            'author' => '',
        ]);


        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $response = $this->post('/books', [
            'title'  => 'Cool title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title'  => 'New Title',
            'author' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);

        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $response = $this->post('/books', [
            'title'  => 'Cool title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}
