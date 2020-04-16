# The assignment

From https://github.com/graciousagency/rick-and-morty-case

Using the API: https://rickandmortyapi.com/

We want to see the following:
- Show all characters that exist (or are last seen) in a given dimension
```shell script
bin/console character:search --location-dimension=C-137
```
- Show all characters that exist (or are last seen) at a given location
```shell script
bin/console character:search --location-name=earth
```
- Show all characters that partake in a given episode
```shell script
bin/console character:search --episode-episode=earth
```
- Showing all information of a character (Name, species, gender, last location, dimension, etc)
```shell script
bin/console character:show 1
```

## The commands
To view a list of the available commands, type `bin/console list` in your console. To view help for a command, type `bin/console <command> --help`. 

##### Output as JSON
To output results as JSON, use the `--json` option. Progress bars are redirected to stderr when using JSON output, so they'll be visible, but they won't show up if the output is redirected to a file:
```shell script
bin/console character:list --json > characters.json
```
##### Disable caching
To disable caching, pass the --no-cache option to a command.
```shell script
bin/console character:list --no-cache
```
##### Hide progress bars
To hide the progress bars, use the `--no-progress` option.

#### cache:clear
```shell script
bin/console cache:clear
```
This is the simplest of the commands. It clears the request cache by deleting the whole cache directory. It'll be recreated automatically.

### Show commands
For each of the endpoints, there is a **show** command which displays information about an item. It takes just one argument: the id of the item. Output as JSON is possible using the --json option.
#### character:show
Shows information about a character. For example:
```shell script
bin/console character:show 1

Rick Sanchez [1]
================

Status: Alive
Species: Human
Type: 
Gender: Male
Origin: Earth (C-137)
Location: Earth (Replacement Dimension)
```
URLs are only displayed with verbose output:
```shell script
bin/console character:show 1 -v

Rick Sanchez [1]
================

Status: Alive
Species: Human
Type: 
Gender: Male
Origin: Earth (C-137) (https://rickandmortyapi.com/api/location/1)
Location: Earth (Replacement Dimension) (https://rickandmortyapi.com/api/location/20)
Image: https://rickandmortyapi.com/api/character/avatar/1.jpeg
Link: https://rickandmortyapi.com/api/character/1
Episodes:
 * https://rickandmortyapi.com/api/episode/1
 ...
 * https://rickandmortyapi.com/api/episode/31

```
To get the result as JSON, use the `--json` argument:
```shell script
bin/console character:show 1 --json

{
    "status": "Alive",
    "species": "Human",
    "type": "",
    "gender": "Male",
    "origin": {},
    "location": {},
    "image": "https:\/\/rickandmortyapi.com\/api\/character\/avatar\/1.jpeg",
    "episode": [
        "https:\/\/rickandmortyapi.com\/api\/episode\/1",
        ...
        "https:\/\/rickandmortyapi.com\/api\/episode\/31"
    ],
    "id": 1,
    "name": "Rick Sanchez",
    "url": "https:\/\/rickandmortyapi.com\/api\/character\/1",
    "created": "2017-11-04T18:48:46.250Z"
}
```
#### episode:show
Shows information about an episode.

#### location:show
Shows information about a location.

### List commands
For each of the endpoints, there is a **list** command that displays a list of characters, episodes or locations. It also can be used as a basic search by supplying filters (see the help for the command). Like the **show** command, verbose output (`-v`) can be used for displaying links, and `--json` for JSON output.
### character:list
Displays a list of characters. To view what filters are available, type:
```shell script
bin/console character:list --help
...
      --name[=NAME]        Filter characters by name
      --status[=STATUS]    Filter characters by status
      --species[=SPECIES]  Filter characters by species
      --type[=TYPE]        Filter characters by type
      --gender[=GENDER]    Filter characters by gender
...
```
These filters can be combined to narrow your search. For example, to display all dead Morty's:
```shell script
bin/console character:list --name=morty --status=dead
 9/9 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100% Done.

Big Morty [43]
==============

Status: Dead
Species: Human
Type: 
Gender: Male
Origin: unknown
Location: Citadel of Ricks
...
Toxic Morty [360]
=================

Status: Dead
Species: Humanoid
Type: Morty's toxic side
Gender: Male
Origin: Detoxifier
Location: Earth (Replacement Dimension)
```
### episode:list
Displays a list of episodes.
### location:list
Displays a list of locations.

## Search commands
For each of the endpoints, there is a **search** command that also searches one or two other endpoints using their available filters. If no filters are supplied (or just the filters of the main client), it behaves just like the **list** command.

### character:search
Searches for characters.
##### Available filters:
```shell script
bin/console character:search --help
...
      --name[=NAME]                              Filter characters by name
      --status[=STATUS]                          Filter characters by status
      --species[=SPECIES]                        Filter characters by species
      --type[=TYPE]                              Filter characters by type
      --gender[=GENDER]                          Filter characters by gender
      --episode-name[=EPISODE-NAME]              Filter by episode name
      --episode-episode[=EPISODE-EPISODE]        Filter by episode episode
      --location-name[=LOCATION-NAME]            Filter by location name
      --location-type[=LOCATION-TYPE]            Filter by location type
      --location-dimension[=LOCATION-DIMENSION]  Filter by location dimension
...
```
##### Example: Search for all humans living on earth
```shell script
bin/console character:search --species=human --status=alive --location-name=earth
``` 
##### Example: Search for all aliens in dimension C-137
```shell script
bin/console character:search --species=alien --location-dimension=C-137
``` 
### episode:search
Searches for episodes.
##### Available filters:
```shell script
bin/console episode:search --help
...
      --name[=NAME]                            Filter episodes by name
      --episode[=EPISODE]                      Filter episodes by episode
      --character-name[=CHARACTER-NAME]        Filter by character name
      --character-status[=CHARACTER-STATUS]    Filter by character status
      --character-species[=CHARACTER-SPECIES]  Filter by character species
      --character-type[=CHARACTER-TYPE]        Filter by character type
      --character-gender[=CHARACTER-GENDER]    Filter by character gender
...
```
##### Example: all episodes in which female robots appear, as JSON
```shell script
bin/console episode:search --character-species=robot --character-gender=female --json
```
### location:search
Searches for locations.
##### Available filters:
```shell script
bin/console location:search --help
...
      --name[=NAME]                            Filter locations by name
      --type[=TYPE]                            Filter locations by type
      --dimension[=DIMENSION]                  Filter locations by dimension
      --character-name[=CHARACTER-NAME]        Filter by character name
      --character-status[=CHARACTER-STATUS]    Filter by character status
      --character-species[=CHARACTER-SPECIES]  Filter by character species
      --character-type[=CHARACTER-TYPE]        Filter by character type
      --character-gender[=CHARACTER-GENDER]    Filter by character gender
...
```
##### Example:
## Techniques
For this project I used:
- PHP 7.2
- [Composer](https://getcomposer.org/) for managing dependencies
- [Symfony Console](https://symfony.com/doc/current/components/console.html) to create the CLI commands
- [Symfony DependencyInjection](https://symfony.com/doc/current/components/dependency_injection.html) with [Symfony Config](https://symfony.com/doc/current/components/config.html) and [Symfony Yaml](https://symfony.com/doc/current/components/yaml.html) to manage dependency injection.
- [Guzzle](https://github.com/guzzle/guzzle) as an API client with [guzzle-cache-middleware
](https://github.com/Kevinrob/guzzle-cache-middleware) and [Flysystem](https://flysystem.thephpleague.com/v1/docs/) to cache the responses from the REST API. 

For development, I used [PHP Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [PHPStan](https://phpstan.org/) to look for (potential) bugs.

### How?
I aimed to make good use of the Symfony services configuration. That's why there are no separate classes for `character:show`, `episode:show` and `location:show`. They're all instances of the same Command, but with differently configured clients. The same goes for the rest of the commands. Except for `cache:clear`, they all come in three versions, one for each endpoint.
The Search command uses a search service instead of a single client. This search service contains two or three clients and a mapping to tell the search how to get from one client to the main client. Again all configuration is done in `services.yaml`.

The results from the client or search service are then rendered by the ModelRenderer, which is configured in `parameters.yaml`.
