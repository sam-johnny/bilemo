<?php



namespace App\Helper\Paginated;

use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

class PaginatedHelper
{
 public function paginatedCollection($repository, $route, $limitPaginated = 5): PaginatedRepresentation
 {
     $countItems = count($repository);
     $totalPage = ceil($countItems/$limitPaginated);

     return new PaginatedRepresentation(
         new CollectionRepresentation($repository),
         $route,
         [],
         1,
         $limitPaginated,
         $totalPage
     );
 }
}