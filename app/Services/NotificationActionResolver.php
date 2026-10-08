<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\ForumThread;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\UserNotification;
use App\Support\BinaryUuid;
use InvalidArgumentException;

final class NotificationActionResolver
{
    public function resolve(User $user, UserNotification $notification): ?string
    {
        $data = is_array($notification->data) ? $notification->data : [];

        try {
            return match ($notification->notification_type) {
                'identity_verification_result', 'identity_verified' => $user->hasRole('youth') ? route('youth.identity-verification') : null,
                'community_verification_result', 'community_approved' => $this->ownedCommunity($user, $data['organization_id'] ?? null),
                'community_membership_result' => $this->publicCommunity($data['organization_id'] ?? null),
                'activity_registration_result', 'activity_participation_completion' => $this->publicActivity($data['activity_id'] ?? null),
                'activity_certificate_issued' => $this->ownedCertificate($user, $data['certificate_id'] ?? null),
                'activity_verification_result' => $this->managedActivity($user, $data['activity_id'] ?? null),
                'forum_thread_replied', 'forum_content_moderated' => $this->publicForumThread($data['thread_id'] ?? null),
                'program_proposal_reviewed', 'program_logbook_reviewed', 'financial_report_reviewed', 'program_evaluation_finalized' => $this->managedProgram($user, $data['program_id'] ?? null),
                default => null,
            };
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    private function ownedCommunity(User $user, mixed $id): ?string
    {
        $organization = $this->find(Organization::class, $id);

        return $organization && hash_equals($organization->created_by_user_id, $user->getKey()) ? route('youth.communities.show', $organization) : null;
    }

    private function publicForumThread(mixed $id): ?string
    {
        $thread = $this->find(ForumThread::class, $id);
        return $thread && $thread->status !== ForumThread::HIDDEN ? route('forum.show', $thread) : route('forum.index');
    }

    private function publicCommunity(mixed $id): ?string
    {
        $organization = $this->find(Organization::class, $id);

        return $organization?->review_status === Organization::REVIEW_APPROVED && $organization->operational_status === Organization::OPERATIONAL_ACTIVE ? route('communities.show', $organization) : null;
    }

    private function publicActivity(mixed $id): ?string
    {
        $activity = $this->find(Activity::class, $id);

        return $activity?->review_status === Activity::REVIEW_APPROVED && $activity->publication_status === Activity::PUBLICATION_PUBLISHED ? route('activities.show', $activity) : null;
    }

    private function managedActivity(User $user, mixed $id): ?string
    {
        $activity = $this->find(Activity::class, $id);

        return $activity && $this->manages($user, $activity->organization_id) ? route('manager.activities.show', [$activity->organization, $activity]) : null;
    }

    private function managedProgram(User $user, mixed $id): ?string
    {
        $program = $this->find(Program::class, $id);

        return $program && $this->manages($user, $program->organization_id) ? route('manager.programs.show', [$program->organization, $program]) : null;
    }

    private function ownedCertificate(User $user, mixed $id): ?string
    {
        $certificate = $this->find(UserCertificate::class, $id);

        return $certificate && hash_equals($certificate->user_id, $user->getKey()) ? route('youth.certificates.show', $certificate) : null;
    }

    private function manages(User $user, string $organizationId): bool
    {
        return OrganizationMembership::query()->where('user_id', $user->getKey())->where('organization_id', $organizationId)
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
    }

    private function find(string $model, mixed $id): mixed
    {
        return is_string($id) ? $model::query()->find(BinaryUuid::bytes($id)) : null;
    }
}
