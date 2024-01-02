<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\Api\BlogController;
use Mockery;

class BlogControllerTest extends TestCase
{
    public function testBlogShort()
    {
        $blogController = new BlogController();
        $actualResponse = json_decode($blogController->blogShort()->getContent(), true);
        $expectedResponse = [
            "blogs" =>  [
              [
                "id" => 2,
                "category" => "Fashion",
                "blogtitle" => "Reiciendis saepe sin",
                "thumbnail" => "public/uploads/blog/13460b72f2aa60d9ff31eebe475b1cd8.jpg",
              ],
            [
                "id" => 3,
                "category" => "Fashion",
                "blogtitle" => "ease of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum",
                "thumbnail" => "public/uploads/blog/010e1d34885629437c7e4b57d671cdfe.jpg",
              ],
             [
                "id" => 4,
                "category" => "Business",
                "blogtitle" => "orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specim",
                "thumbnail" => "public/uploads/blog/d8985dad2250d8f60870c16435049626.jpg",
              ],
              [
                "id" => 5,
                "category" => "Fashion",
                "blogtitle" => "Sint atque est labo",
                "thumbnail" => "public/uploads/blog/d9311e59ecb4c8dbe048d7ccd183fd45.jpg",
              ],
              [
                "id" => 6,
                "category" => "Business",
                "blogtitle" => "In sint suscipit vo",
                "thumbnail" => "public/uploads/blog/1ab1ab5701da0072d9d92118f5e4c72f.jpg",
              ],
             [
                "id" => 7,
                "category" => "Fashion",
                "blogtitle" => "Nemo ut sit ullamco",
                "thumbnail" => "public/uploads/blog/7a27f4c2b510afce451eb24990e7cacd.jpg",
              ],
              [
                "id" => 8,
                "category" => "Fashion",
                "blogtitle" => "Labore voluptates es",
                "thumbnail" => "public/uploads/blog/a14ce4bdba7d011544e47caefa654ca7.jpg",
              ],
             [
                "id" => 9,
                "category" => "Education",
                "blogtitle" => "Dignissimos adipisicsss",
                "thumbnail" => "public/uploads/blog/51f7cfe308078701415385b8ea57036f.jpg",
              ],
              [
                "id" => 10,
                "category" => "Education",
                "blogtitle" => "Quis in cumque aut o",
                "thumbnail" => "public/uploads/blog/342befd93a6cfa15fa179db496b79cba.jpg",
              ],
              [
                "id" => 11,
                "category" => "Education",
                "blogtitle" => "Repellendus Elit a",
                "thumbnail" => "public/uploads/blog/5bef27f8dc10fb3a0fdc5820cdca292f.jpg",
              ],
            ]
          ];

        $this->assertEquals($expectedResponse, $actualResponse);
    }

    public function testBlogDetails()
    {
        $blogController = new BlogController();
        $actualResponse = json_decode($blogController->blogDetails(2)->getContent(), true);

        $expectedResponse = [
            'blogs' => [
                'category' => 'Fashion',
                'blogtitle' => 'Reiciendis saepe sin',
                'thumbnail' => 'public/uploads/blog/13460b72f2aa60d9ff31eebe475b1cd8.jpg',
                'details' => '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',
                'is_published' => 0,
                'created_by' => 1,
            ],
        ];

        // Perform the assertion
        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
