<template>
    <div class="board-row">
        <div
            v-for="(stage, i) in stages"
            :class="`board-col p-2 gap-4 pb-10 bg-gray-100 dark:bg-gray-800 ${
                is_dragging ? 'dragging' : ''
            } ${over_index == i ? 'over' : ''}`"
            :style="{ '--color': stage.color }"
            :key="i"
            @drop="onDrop($event, i)"
            @dragover.prevent="onOver($event, i)"
            @dragenter.prevent
        >
            <div class="title text-neutral-800 dark:text-neutral-100">
                {{ stage.name }}
            </div>
            <StageLead
                v-for="(lead, y) in stage.leads.data"
                :lead="lead"
                :key="y"
                :index="y"
                @dragstart="startDrag($event, i, lead)"
            />
            <LoadingCard v-if="stage.loading" />
            <template v-if="!stage.loading && stage.leads.next_cursor">
                <a
                    class="py-3 w-full text-center text-xs"
                    href="#"
                    @click.prevent="fetchLeads(i)"
                >
                    Carregar mais
                </a>
            </template>
        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from 'vuex';
import LoadingCard from './-loading-card.vue';
import StageLead from './-stage-lead.vue';

export default {
    props: ['campaign'],
    data() {
        return {
            is_dragging: false,
            over_index: null,
            dragging: null,
            dragging_index: null,
        };
    },
    components: {
        LoadingCard,
        StageLead,
    },
    computed: {
        ...mapGetters('campaign', ['stages']),
    },
    created() {
        const gradient_colors = this.generateGradient(
            this.campaign.stages.length
        );
        let stages = [];
        for (let i = 0; i < this.campaign.stages.length; i++) {
            stages.push({
                name: this.campaign.stages[i],
                index: i,
                color: gradient_colors[i],
                leads: [],
                loading: true,
            });
        }
        this.setStages(stages);
        this.fetchLeads();
    },
    methods: {
        ...mapMutations('campaign', ['setStages']),
        ...mapActions('campaign', ['fetchStageLeads', 'saveLead']),
        fetchLeads(index = null) {
            if (!index) {
                for (let i = 0; i < this.stages.length; i++) {
                    this.fetchStageIndex(i);
                }
            } else {
                this.fetchStageIndex(index);
            }
        },
        fetchStageIndex(index = null) {
            this.fetchStageLeads(index);
        },
        onOver(event, index) {
            this.over_index = index;
        },
        onDrop(event, stage) {
            if (this.dragging_index != stage) {
                this.saveLead({
                    lead_id: this.dragging.id,
                    new_stage: stage,
                });
                const leads = this.stages[this.dragging_index].leads;
                this.stages[this.dragging_index].leads.data = leads.data.filter(
                    (x) => x.id !== this.dragging.id
                );
                this.stages[stage].leads.data.unshift(this.dragging);
            }
            this.dragging = null;
            this.is_dragging = false;
            this.dragging_index = null;
            this.over_index = null;
            this.dragging = null;
        },
        startDrag(event, index, item) {
            this.over_index = index;
            this.is_dragging = true;
            this.dragging = item;
            this.dragging_index = index;
        },
        generateGradient(qty) {
            const initialColor = '#ffcc00';
            const finalColor = '#38be00';
            const initialR = parseInt(initialColor.slice(1, 3), 16);
            const initialG = parseInt(initialColor.slice(3, 5), 16);
            const initialB = parseInt(initialColor.slice(5, 7), 16);
            const finalR = parseInt(finalColor.slice(1, 3), 16);
            const finalG = parseInt(finalColor.slice(3, 5), 16);
            const finalB = parseInt(finalColor.slice(5, 7), 16);
            const colors = [];
            const incrementR = (finalR - initialR) / (qty - 1);
            const incrementG = (finalG - initialG) / (qty - 1);
            const incrementB = (finalB - initialB) / (qty - 1);
            for (let i = 0; i < qty; i++) {
                const r = Math.round(initialR + incrementR * i);
                const g = Math.round(initialG + incrementG * i);
                const b = Math.round(initialB + incrementB * i);
                const color = `#${this.componentToHex(r)}${this.componentToHex(
                    g
                )}${this.componentToHex(b)}`;
                colors.push(color);
            }

            return colors;
        },
        componentToHex(c) {
            const hex = c.toString(16);
            return hex.length == 1 ? '0' + hex : hex;
        },
    },
};
</script>

<style lang="scss">
.dark .board-row {
    &::-webkit-scrollbar {
        width: 8px;
    }

    &::-webkit-scrollbar-track {
        background: #545454;
    }

    &::-webkit-scrollbar-thumb {
        background-color: #212121;
        border-radius: 9px;
        border: 5px none #616161;
    }
}
.board-row {
    gap: 10px;
    display: flex;
    overflow-x: auto;
    max-width: 100%;
    flex-direction: row;
    margin-bottom: 300px;
    padding: 1px;
    .board-col {
        min-height: 500px;
        width: 350px;
        min-width: 350px;
        max-width: 350px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        border-top: 8px solid var(--color);
        outline: 1px solid #e1e1e1;

        &.dragging {
            opacity: 0.25;
            transition: 0.4s;

            &.over {
                opacity: 0.8;
            }
        }

        .title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
            padding: 5px;
        }
    }
}
</style>
