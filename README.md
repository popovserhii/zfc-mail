# ZF2 Mail Module

## Usage
Main feature is `action` field which allow use named values for sending message without id reference.
The best way for naming is **user_add** or **status_inProgress** where the first part is *module mnemo* and the second is
*action mnemo*. 

In your `Listener` you can use this something like this 
```
/** @var Mail $mail */
$mail = $em->getRepository(Mail::class)->findOneBy(['action' => 'user_add', 'type' => Mail::TYPE_MAIL]);
$mailService->send($mail, [$user->getEmail()], ['user' => $user, 'password' => $e->getParam('password')]);
```