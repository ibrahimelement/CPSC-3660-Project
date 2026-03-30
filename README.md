### Project structure

`/lib`: for random functional methods like auth, validation, helpers - etc

`/pages`: this is where most of the actual logic is, the domain logic for the backend portion - queries are ran here and CRUD operations are supported - we have one of each here, create/delete/edit/login/logout/getPlayers

`/partials`: shared component logic for HTML

`/views`: just a bunch of PHP/HTML logic, dumb logic here that doesn't do anything on the backend

`/sql`: contains the schema definitions, was not hand-written, used dbdiagram.io to generate it from our original visual diagram created + a seed script

