# API

The API will be here.

Refer to the [Getting Started Guide](https://api-platform.com/docs/distribution) for more information.

## Notes

This project contains 
- server API for managing users and groups (using API Platform)
- CLI client commands allowing to add/view/edit/delete users, groups and modify content of groups on server. All commands are calling server with HTTP requests so in general it's easy to separate them to another application.

### CLI commands
> user:add `name` `email`
> user:get `id`
> user:update `id` `name` `email`
> user:delete `id`
> group:add `name`
> group:get `id`
> group:update `id` `name`
> group:delete `id`
> group:report
> group:add_user `group_id` `user_id`
> group:delete_user `group_id` `user_id`

