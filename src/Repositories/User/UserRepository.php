<?php

namespace Gingerminds\LaravelCore\Repositories\User;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Gingerminds\LaravelCore\Repositories\AbstractRepository;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use InvalidArgumentException;

/**
 * @extends AbstractRepository<User>
 * @implements RepositoryInterface<User>
 */
class UserRepository extends AbstractRepository implements RepositoryInterface
{
    public function getModelClass(): string
    {
        return User::class;
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof User) {
            throw new InvalidArgumentException('ResourceModelInterface must be an instance of User');
        }

        if (!$request instanceof FormRequestInterface) {
            return $resourceModel;
        }

        // Mise à jour des champs basiques
        $resourceModel->fill($request->only(['email']));

        // Mot de passe optionnel: on ne met à jour que s'il est fourni
        if ($request->filled('password')) {
            $resourceModel->password = $request->input('password');
        }

        $resourceModel->save();

        // Synchronisation des rôles s'ils sont fournis
        if (is_array($request->input('roles'))) {
            $resourceModel->syncRoles($request->input('roles', []));
        }

        // Gestion de l'association du contributor s'il est fourni
        if ($request->has('contributor_id')) {
            $contributorSelector = $request->input('contributor_id');

            $contributor = null;

            // Création d'un nouveau contributor si demandé
            if ($contributorSelector === '__new__') {
                // Nettoyer tout lien existant sur ce user
                Contributor::query()->where('user_id', $resourceModel->id)->update(['user_id' => null]);
                $contributor = new Contributor();
            } elseif ($contributorSelector) {
                // Lier un contributor existant
                // S'assurer qu'aucun autre contributor n'est relié à ce user
                Contributor::query()
                    ->where('user_id', $resourceModel->id)
                    ->whereKeyNot($contributorSelector)
                    ->update(['user_id' => null]);
                $contributor = Contributor::query()->find($contributorSelector);
            }

            if ($contributor instanceof Contributor) {
                $payload = [
                    // Les champs lastname/firstname ne sont pas nullable en DB; valeurs vides acceptées
                    'firstname' => (string) $request->input(
                        'contributor_firstname',
                        ''
                    ),
                    'lastname' => (string) $request->input(
                        'contributor_lastname',
                        ''
                    ),
                ];
                if ($request->filled('contributor_trigram')) {
                    $payload['trigram'] = (string) $request->input('contributor_trigram');
                }
                if ($request->filled('contributor_civility')) {
                    $payload['civility'] = (string) $request->input('contributor_civility');
                }

                $contributor->fill($payload);
                $contributor->user_id   = $resourceModel->id;
                $contributor->save();
            }
        }

        return $resourceModel;
    }
}
