<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



## Contractors Village


### Projects
#### Endpoints

##### List Projects
Endpoint: /api/projects
Method: GET
Description: Retrieve a list of all projects belonging to the authenticated user.
Headers:
Authorization: Bearer YOUR_SANCTUM_TOKEN
Response:
Status Code: 200 OK
Content: json

[
  {
    "id": 1,
    "user_id": 1,
    "title": "Project Title",
    "description": "Project description goes here.",
    "status": "active",
    "total_cost": 5000.00,
    "created_at": "2024-08-01T00:00:00.000000Z",
    "updated_at": "2024-08-01T00:00:00.000000Z"
  },
  ...
]

##### Get Specific Project
Endpoint: /api/projects/{id}
Method: GET
Description: Retrieve details of a specific project by its ID.
Headers:
Authorization: Bearer YOUR_SANCTUM_TOKEN
Parameters:
id (path) - The ID of the project.
Response: Status Code: 200 OK
Content: json

{
  "id": 1,
  "user_id": 1,
  "title": "Project Title",
  "description": "Project description goes here.",
  "status": "active",
  "total_cost": 5000.00,
  "created_at": "2024-08-01T00:00:00.000000Z",
  "updated_at": "2024-08-01T00:00:00.000000Z"
}

##### Create Project
Endpoint: /api/projects
Method: POST
Description: Create a new project record.
Headers:
Authorization: Bearer YOUR_SANCTUM_TOKEN
Content-Type: application/json
Request Body:json

{
  "title": "New Project Title",
  "description": "Detailed description of the new project.",
  "status": "active",
  "total_cost": 3000.00
}

Response:
Status Code: 201 Created
Content:json

{
  "id": 1,
  "user_id": 1,
  "title": "New Project Title",
  "description": "Detailed description of the new project.",
  "status": "active",
  "total_cost": 3000.00,
  "created_at": "2024-08-01T00:00:00.000000Z",
  "updated_at": "2024-08-01T00:00:00.000000Z"
}

##### Update Project
Endpoint: /api/projects/{id}
Method: PUT
Description: Update the details of a specific project.
Headers:
Authorization: Bearer YOUR_SANCTUM_TOKEN
Content-Type: application/json
Parameters:
id (path) - The ID of the project to be updated.
Request Body: json

{
  "title": "Updated Project Title",
  "description": "Updated description of the project.",
  "status": "completed",
  "total_cost": 3500.00
}

Response:
Status Code: 200 OK
Content: json

{
  "id": 1,
  "user_id": 1,
  "title": "Updated Project Title",
  "description": "Updated description of the project.",
  "status": "completed",
  "total_cost": 3500.00,
  "created_at": "2024-08-01T00:00:00.000000Z",
  "updated_at": "2024-08-02T00:00:00.000000Z"
}

##### Delete Project
Endpoint: /api/projects/{id}
Method: DELETE
Description: Delete a specific project record.
Headers:
Authorization: Bearer YOUR_SANCTUM_TOKEN
Parameters:
id (path) - The ID of the project to be deleted.
Response:
Status Code: 204 No Content