RESTAPI Methods
===============

API Have CRUD methods
==============
Get Teams

 @Route("/api/v1/getTeams/{league}")
 @Method("GET")
 @param $league



Create Team / League

 @Route("/api/v1/createTeam")
 @Method("POST")

Update Team / League

@Route("/api/v1/updateTeam/{id}")
@Method("PUT")

Delete League and all teams inside

@Route("/api/v1/deleteLeague/{name}")
@Method("DELETE")

Doesn't include JWT authentication!

Build using Symfony 3.4
