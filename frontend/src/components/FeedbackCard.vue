<script setup>
import { useAuthStore } from '@/stores/auth'
import { marked } from 'marked'
defineProps(['feedback', 'showFullDescription', 'isLink', 'userVoted'])
const emit = defineEmits(['on-vote-clicked'])
const authStore = useAuthStore()

function formattedDate(date) {
  return new Date(date).toLocaleString()
}

function onVoteClicked() {
  emit('on-vote-clicked')
}
</script>
<template>
  <div class="feedback-card" :class="{ 'is-link': isLink }">
    <!-- <div class="feedback-card-first-half"> -->
    <div class="rating clickable" :class="{ voted: userVoted }" @click="onVoteClicked">
      <i class="bi bi-chevron-up"></i>
      <span class="vote-count">{{ feedback['vote_count'] }}</span>
    </div>

    <div class="feedback-card-middle">
      <div class="feedback-card-title">
        {{ feedback.title }}
      </div>

      <div
        class="feedback-card-desc"
        :class="{ compact: !showFullDescription }"
        v-html="marked.parse(feedback.description)"
      ></div>

      <div class="category">
        {{ feedback.category }}
      </div>
    </div>
    <!-- </div> -->
    <div class="feedback-card-end">
      <i class="bi bi-chat-left-fill"></i>
      <span>{{ feedback['comments_count'] ?? feedback.comments?.length }}</span>
    </div>
  </div>
</template>

<style scoped>
.feedback-card {
  width: 100%;
  padding: 2em 2em;
  box-shadow: 2px 2px 5px 2px light-dark(var(--light-box-shadow), var(--dark-box-shadow));
  border-radius: 0.5em;

  color: light-dark(var(--light-text), var(--dark-text));
  background: light-dark(var(--light-card-bg), var(--dark-card-bg));
  transition:
    box-shadow 0.6s ease-in-out,
    background-color 0.6s ease-in-out;

  display: grid;
  grid-template-columns: 1fr 90% 1fr;
  align-items: center;
  justify-items: start;
}

.rating {
  align-self: self-start;
}

.feedback-card-first-half {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: flex-start;
}

.is-link:hover,
.is-link:active {
  background-color: light-dark(var(--light-card-bg-hover), var(--dark-card-bg-hover));
  box-shadow: 5px 5px 10px 5px
    light-dark(var(--light-box-shadow-hover), var(--dark-box-shadow-hover));
}

.feedback-card-title {
  font-weight: bold;
  font-size: 1.2em;
}

.rating {
  width: 60px;
  border-radius: 15%;
  padding: 0.4em 0.65em;
}

.feedback-card-middle {
  /* align-self: flex-start; */

  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  gap: 0.5em;

  margin-left: 3em;
  margin-right: 2em;
}

.feedback-card-desc {
  color: light-dark(#637196, #999183);
}

.compact {
  text-overflow: ellipsis;

  overflow: hidden;
  white-space: wrap;
}

.feedback-card-end {
  align-self: center;
  justify-self: flex-end;

  display: flex;
  flex-direction: row;
  row-gap: 2em;
}

.feedback-card-end i {
  color: #cdd2ef;
}

.feedback-card-end > * {
  padding: 0.2em;
}

@media screen and (max-width: 600px) {
  .feedback-card {
    flex-direction: column;

    grid-template: 1fr auto / 1fr 1fr;
    align-items: center;
    justify-items: center;
    row-gap: 1em;
  }

  .feedback-card-first-half {
    flex-direction: column-reverse;
  }

  .feedback-card-middle {
    margin: 0em;

    grid-area: 1 / span 2;
  }

  .rating {
    grid-area: 2/1;
    padding: 0;
    justify-self: self-start;

    flex-direction: row;
    row-gap: 5em;
    padding: 0.5em 1em;
    font-size: 0.9em;
  }

  .vote-count {
    padding-left: 1em;
  }

  .feedback-card-end {
    grid-area: 2/2;
  }
}
</style>
