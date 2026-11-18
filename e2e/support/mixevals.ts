import { expect, test } from '@playwright/test';
import type { TestDatabase } from './db';
import { NOW, insertRow, userId } from './insert';
import { USERS } from './seed';

// Mixed evaluation templates for the specs that need one but do not test the
// template form. mixed-evaluations.spec.ts tests the form, and checks that
// insertMixeval() writes what the form writes.

export type Question = {
  type: 'Likert' | 'Sentence' | 'Paragraph' | 'ScoreDropdown';
  title: string;
  instructions: string;
  required: boolean;
  // Likert questions only: the marks of the highest level, whether students
  // see the marks, and one descriptor per level, lowest first.
  marks?: string;
  showMarks?: boolean;
  scale?: readonly string[];
};

export type Section = 'Peer' | 'Self';

export type Mixeval = {
  name: string;
  availability: 'Public' | 'Private';
  zeroMark: boolean;
  selfEval: boolean;
  peer: readonly Question[];
  self: readonly Question[];
};

// The ids of the seeded mixeval_question_types rows.
const TYPE_IDS: Record<Question['type'], number> = {
  Likert: 1,
  Paragraph: 2,
  Sentence: 3,
  ScoreDropdown: 4,
};

// Writes the rows that a save of the Add form writes (see _dataSavePrep and
// _transactionalSave in mixevals_controller.php), and returns the id.
export async function insertMixeval(
  testDatabase: TestDatabase,
  mixeval: Mixeval,
  creator: string = USERS.superAdmin
): Promise<string> {
  return test.step(
    `Insert the mixed evaluation ${mixeval.name}`,
    async () => {
      // The save drops the self-evaluation questions when Self-Evaluation is
      // off, so such data would not show what the form saves.
      expect(mixeval.selfEval || mixeval.self.length === 0, 'self questions need selfEval').toBe(
        true
      );
      const owner = await userId(testDatabase, creator);
      const id = await insertRow(testDatabase, 'mixevals', {
        name: mixeval.name,
        zero_mark: mixeval.zeroMark,
        availability: mixeval.availability.toLowerCase(),
        creator_id: owner,
        created: NOW,
        updater_id: owner,
        modified: NOW,
      });
      // One numbering for both sections: the peer questions first, then the
      // self-evaluation questions.
      const questions = [
        ...mixeval.peer.map((question) => ({ question, selfEval: false })),
        ...mixeval.self.map((question) => ({ question, selfEval: true })),
      ];
      // The save gives every question the number of descriptors of the whole
      // template as its scale_level, not the number of its own. Grades use the
      // scale_level of the descriptors, so this does no harm, but the rows
      // copy it.
      const descriptorCount = questions.reduce(
        (sum, { question }) => sum + (question.scale?.length ?? 0),
        0
      );
      for (const [index, { question, selfEval }] of questions.entries()) {
        const questionId = await insertRow(testDatabase, 'mixeval_questions', {
          mixeval_id: id,
          question_num: index + 1,
          title: question.title,
          instructions: question.instructions,
          mixeval_question_type_id: TYPE_IDS[question.type],
          required: question.required,
          self_eval: selfEval,
          // The form sends marks only for Likert questions, and a fixed 10 for
          // score dropdown questions.
          multiplier:
            question.type === 'Likert'
              ? Number(question.marks ?? 0)
              : question.type === 'ScoreDropdown'
                ? 10
                : 0,
          scale_level: descriptorCount,
          show_marks: question.showMarks ?? false,
        });
        for (const [level, descriptor] of (question.scale ?? []).entries()) {
          await insertRow(testDatabase, 'mixeval_question_descs', {
            question_id: questionId,
            scale_level: level + 1,
            descriptor,
          });
        }
      }
      return id;
    },
    { box: true }
  );
}
