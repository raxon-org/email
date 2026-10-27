{{$register = Package.Raxon.Email:Init:register()}}
{{if(!is.empty($register))}}
{{Package.Raxon.Email:Import:role.system()}}
{{Package.Raxon.Email:Setup:email.install()}}
{{/if}}